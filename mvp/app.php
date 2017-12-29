<?php
	class App {
		var $debug;
		var $db;
		var $url;

		function __construct($debug = false){
			$this->debug = $debug;
			$this->db    = new PDO("sqlite:assets/meja.db", "charset=UTF-8");
			$this->url = "https://www.joshua.ovh/meja/";
			# $this->url = "http://localhost/meja/";
		}



		/**
			*****************
			******************
			*******************
			********************
			* IDEAS HANDLING ****
			********************
			*******************
			******************
			*****************
		**/

		/*
			@var page: TODO: [F] lazy load ideas 10 by 10 
			@return: array of get
		*/
		function getIdeas($page = 0, $sortBy = null, $author = null){
			switch ($sortBy) {
				case 'created_new':
					$q = $this->db->query("SELECT ideas.*, COALESCE(SUM(scores.VALUE), 0) score FROM ideas
										LEFT JOIN scores ON ideas.id = scores.iid
										GROUP BY ideas.id
										ORDER BY created DESC")->fetchAll(PDO::FETCH_OBJ);
					break;
				case 'created_old':
					$q = $this->db->query("SELECT ideas.*, SUM(scores.VALUE) score FROM ideas
										LEFT JOIN scores ON ideas.id = scores.iid
										GROUP BY ideas.id
										ORDER BY created ASC")->fetchAll(PDO::FETCH_OBJ);
					break;
				case 'upvotes':
					$q = $this->db->query("SELECT ideas.*, SUM(scores.VALUE) score FROM ideas
										LEFT JOIN scores ON ideas.id = scores.iid
										GROUP BY ideas.id
										ORDER BY score DESC, created DESC")->fetchAll(PDO::FETCH_OBJ);
					//TODO: = Upvote / (Upvote+Downvote)
					break;
				
				default:
					$q = $this->db->query("SELECT ideas.*, COALESCE(SUM(scores.VALUE), 0) score FROM ideas
										LEFT JOIN scores ON ideas.id = scores.iid
										GROUP BY ideas.id
										ORDER BY created DESC")->fetchAll(PDO::FETCH_OBJ);
					break;
			}
			if($author != null){
				$q = $this->db->query("SELECT ideas.*, SUM(scores.VALUE) score  
										FROM ideas
										LEFT JOIN scores ON ideas.id = scores.iid
										WHERE ideas.author = \"$author\"
										GROUP BY ideas.id
										ORDER BY created DESC")->fetchAll(PDO::FETCH_OBJ);
			}
			return $q;
		}
		function getIdeasByAuthor($author = null){
			if($author != null){
				$q = $this->db->query("SELECT ideas.*, SUM(scores.VALUE) score  
										FROM ideas
										LEFT JOIN scores ON ideas.id = scores.iid
										WHERE ideas.author = \"$author\"
										GROUP BY ideas.id
										ORDER BY created DESC")->fetchAll(PDO::FETCH_OBJ);
			}
			return $q;
		}

		/*
			@var id
			@var timestamp
			@return: idea as object or false
		*/
		function getIdea($id, $timestamp){
			$q = $this->db->query("SELECT * FROM ideas WHERE id = $id AND created = $timestamp");
			return $q->fetch(PDO::FETCH_OBJ);
		}

		/*
			@return: boolean
		*/
		function publishIdea($title, $cat, $content, $user){
			$title   = htmlentities(addslashes($title));
			$content = nl2br(htmlentities(addslashes($content)));
			$user    = json_decode($user)->email;
			$color   = dechex(rand(0x000000, 0xFFFFFF));
			$time    = (int)time();

			try{
				$this->db->exec("INSERT INTO ideas (title, content, author, icon, hex, created) VALUES (\"$title\", \"$content\", \"$user\", \"$cat\", \"$color\", $time)");
				$this->db->exec("INSERT INTO scores (iid, value, email, ip) VALUES (".(int)$this->db->lastInsertId().", 0, 'meja@root', '0')");
				return true;
			}catch(PDOException $e){
				return false;
			}
		}

		function vote($iid, $value, $user){
			//TODO: Vote system (value: +1 ou -1)
			if(!$this->hasVoted($iid, $user)){
				$this->db->exec("INSERT INTO scores (iid, value, email, ip) VALUES (".(int)$iid.", ".(int)$value.", \"$user\", \"".$_SERVER['REMOTE_ADDR']."\")");

				return true;
			}else{
				return false;
			}

		}
		function hasVoted($iid, $uid){
			$q = $this->db->query("SELECT value FROM scores WHERE email = '".$uid."' AND iid = ". (int)$iid)->fetch(PDO::FETCH_OBJ);
			if($q){
				return ["hasVoted" => true, "value" => $q->value];
			}else{
				return false;
			}
		}
		function getScore($iid){
			$q = $this->db->query("SELECT SUM(scores.VALUE) score FROM scores WHERE scores.iid = ". (int)$iid)->fetch(PDO::FETCH_OBJ);
			return $q->score;
		}


		/**
			********************
			*********************
			**********************
			***********************
			* COMMENTS HANDLING ****
			***********************
			**********************
			*********************
			********************
		**/
		function getComments($pid){
			$q = $this->db->query("SELECT * FROM comments where pid = $pid and is_update = 0");
			$r = $q->fetchAll(PDO::FETCH_OBJ);

			$comments_by_id = [];
			foreach($r as $comment){
				$comments_by_id[$comment->id] = $comment;
			}
			foreach($r as $k => $comment){
				if($comment->parent_id != 0){
					$comments_by_id[$comment->parent_id]->children[] = $comment;
					unset($r[$k]);
				}
			}
			return $r;
		}

		function getUpdates($pid){
			$q = $this->db->query("SELECT * FROM comments where pid = $pid AND is_update = 1 ORDER BY created DESC");
			return $q->fetchAll(PDO::FETCH_OBJ);
		}

		function publishComment($pid, $content, $reply, $is_update, $author){
			$time = (int)time();
			try{
				$this->db->exec("INSERT INTO comments (pid, parent_id, content, created, author, is_update) VALUES ($pid, $reply, \"$content\", $time, '$author', $is_update)");

				$idea = $this->getIdeaById($pid);

				$header = "From: \"Meja\"<me@joshua.ovh>"."\r\n";
				$header.= "MIME-Version: 1.0\r\n"; 
				$header.= "Content-Type: text/html; charset=utf-8\r\n"; 
				$header.= "X-Priority: 1\r\n"; 

				# var_dump(func_get_args());

				if($is_update === 0 && $author != $idea->author){
					mail($idea->author, "New feedback on your idea!", "You received a new reaction on your idea: <a href='https://www.joshua.ovh/meja/view.php?i=".$idea->id."&t=".$idea->created."' target='_blank'>".stripslashes($idea->title)."</a><br>Let's answer to your early users!<br><br><i>These emails are kind of rude and as early users we would like to thank you for using Meja, we're currently working on it :)</i>", $header);
					
				}
				if($reply != 0){
					$comment = $this->getCommentById($reply); //Get main comment where answers come from

					$responses = $this->getResponseByParentId($reply); //List of responses

					# var_dump($responses);

					$ar = [];
					foreach($responses as $response){
						if($response->author != $comment->author && $author != $response->author){
							if(!in_array($response->author, $ar)){
								mail($response->author, "New answer to one of your feedback!", "You sent a feedback on: <a href='https://www.joshua.ovh/meja/view.php?i=".$idea->id."&t=".$idea->created."' target='_blank'>".stripslashes($idea->title)."</a> someone answered to you.<br>Let's answer! :)", $header);
								array_push($ar, $response->author);
							}

						}
					}


				}

				return true;
			}catch(PDOEXception $e){
				return false;
			}
		}
		//TODO: Remove comment and idea


		function getIdeaById($id){
			return $this->db->query("SELECT * FROM ideas WHERE id = ".(int)$id)->fetch(PDO::FETCH_OBJ);
		}
		function getCommentById($id){
			return $this->db->query("SELECT * FROM comments WHERE id = ".(int)$id)->fetch(PDO::FETCH_OBJ);
		}
		function getResponseByParentId($pid){
			return $this->db->query("SELECT author FROM comments WHERE parent_id = ".(int)$pid)->fetchAll(PDO::FETCH_OBJ);
		}

		/**
			*****************
			******************
			*******************
			********************
			* LOGIN HANDLING ****
			********************
			*******************
			******************
			*****************
		**/
		function sendLink($email){
			$token = md5(uniqid().rand(0, 255));
			$time  = (int)time();
			$this->db->exec("INSERT INTO tokens (token, emailaddr, created, ip, phm) VALUES ('".$token."', \"".$email."\", ".$time.", '".$_SERVER['REMOTE_ADDR']."', '". md5(strtolower(trim($email))) ."')");

			$header = "From: \"Meja\"<me@joshua.ovh>"."\r\n";
			$header.= "MIME-Version: 1.0\r\n"; 
			$header.= "Content-Type: text/html; charset=utf-8\r\n"; 
			$header.= "X-Priority: 1\r\n"; 
			mail($email, "Here's your access to the platform!", "It works for one computer only but 7d of full access to the platform. Click here using the device you want to access Meja: <a href=\"".$this->url."login.php?token=".$token."&email=".$email."\" target='_blank'>log in</a>", $header);
		}

		function login($token, $email){
			$token = htmlentities(addslashes($token));
			$q = $this->db->query("SELECT * FROM tokens WHERE token = '$token' AND emailaddr = \"$email\"")->fetch(PDO::FETCH_OBJ);
			if($q){
				$used = (int) $q->used;
				$id   = (int) $q->id;
				$ip   = (int) $q->ip;
				$timestamp = (int) $q->created;
				if($used == 0){
					$e = $this->db->exec("UPDATE tokens SET used = 1 WHERE id = $id");
					echo "<script>Cookies.set('logged', {token: \"".$token."\", email: \"".$email."\", created: ".(int)$q->created."}, {expires: 7});</script>";
				}else{
					echo "already used";
				}
			}else{
				echo "not found";
			}
		}

		/*
		 * @var: $cookie => JSON string
		*/
		function verify($cookie, $ajax = true){
			if(!empty($cookie)){
				$cookie = json_decode($cookie);
			}else{
				$cookie = null;
				if(!$ajax){
					return "error";
					exit;
				}else{
					echo "error";
					exit;
				}
			}
			try{
				$q = $this->db->query("SELECT id, created FROM tokens WHERE token = \"".$cookie->token."\" AND emailaddr = \"".$cookie->email."\"")->fetch(PDO::FETCH_OBJ);
			}catch(PDOException $err){
				if(!$ajax){
					return "error";
				}else{
					echo "error";
					exit;
				}
			}
			if($q){
				if($q->created + 3600*24*7 > time()){
					if(!$ajax){
						return "ok";
					}else{
						echo "ok";
					}
				}else{
					if(!$ajax){
						return "error";
					}else{
						echo "error";
					}
				}
			}
		}

		function getUserByHash($hash){
			$q = $this->db->query("SELECT emailaddr FROM tokens WHERE phm = '". $hash ."'")->fetch(PDO::FETCH_OBJ);
			return $q->emailaddr;
		}

		/**
			******************
			*******************
			********************
			*********************
			* SEARCH HANDLING ****
			*********************
			********************
			*******************
			******************
		**/



		/**
			******************
			*******************
			********************
			*********************
			* EXPORT HANDLING ****
			*********************
			********************
			*******************
			******************
		**/
		function export($type, $id){
			switch ($type) {
				case 'my_comments':
					$q = $this->db->query("SELECT * FROM comments WHERE author = \"$id\"")->fetchAll(PDO::FETCH_OBJ);
					echo json_encode($q);
					break;
				case 'received_comments':
					$q = $this->db->query("SELECT id FROM ideas WHERE author = \"$id\"")->fetchAll(PDO::FETCH_ASSOC); //Get ideas id from user
					foreach($q as $k => $cid){
						$cid = (int) $cid['id'];
						$c = $this->db->query("SELECT id, pid, parent_id, content, created, is_update FROM comments WHERE pid = $cid AND author != \"$id\" ")->fetchAll(PDO::FETCH_OBJ);
						$rc[] = $c;
					}
					echo json_encode($rc);
					break;
				case 'ideas':
					$q = $this->db->query("SELECT * FROM ideas WHERE author = \"$id\"")->fetchAll(PDO::FETCH_OBJ);
					$s = $this->db->query("SELECT iid, value FROM scores")->fetchAll(PDO::FETCH_OBJ); //sort by score
					foreach ($q as $idea){ $idea->score = 0; }
					foreach($q as $idea){
						foreach($s as $k => $v){
								if($idea->id == $v->iid){
									$idea->score += (int)$v->value;
								}
						}
					}
					echo json_encode($q);
					break;
				default:
					echo "error";
					break;
			}
		}




		/**
			*****************
			******************
			*******************
			********************
			* MISCEANELLOUS *****
			********************
			*******************
			******************
			*****************
		**/
		function shorter($text, $chars_limit){
		    // Check if length is larger than the character limit
		    if (strlen($text) > $chars_limit){
		        // If so, cut the string at the character limit
		        $new_text = substr($text, 0, $chars_limit);
		        // Trim off white space
		        $new_text = trim($new_text);
		        // Add at end of text ...
		        return $new_text . "...";
		    }else{
		    	return $text;
		    }
		}

		function beautify($text) {
    		$regex = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
		    return preg_replace_callback($regex, function ($matches) {
		        return "<a href='{$matches[0]}' rel='nofollow' target='_blank'>{$matches[0]}</a>";
		    }, $text);
		}

	}
?>