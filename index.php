<?php
// Vote folder:
$vote_folder = "/votes";
$app_title = "Shitty Vote";
session_start();


// Read the JSON file
$menujson = file_get_contents("menu.json");
$menu = json_decode($menujson, true);
$voteFile = $vote_folder . "/votes.txt";

// Function to get votes
function getVotes() {
    global $voteFile;
    if (!file_exists($voteFile)) {
        file_put_contents($voteFile, "");
        return [];
    }
    return json_decode(file_get_contents($voteFile), true);
}

function saveVote($section, $content) {
    global $voteFile;
    if($_SESSION[$section]['voted'])
      return;

    $votes = getVotes();
    if(empty($votes)){
	$votes[$content] = 0;
    }else{
       if(!isset($votes[$content])){
          $votes[$content] = 0;
       }
    }

    $votes[$content]++;

    file_put_contents($voteFile, json_encode($votes));
    $_SESSION[$section]['voted'] = true;
}


function getEntryVotes($contentmd5){
   $votes = getVotes();
   if(empty($votes))
	return 0;

   if(!isset($votes[$contentmd5]))
	return 0;
   return $votes[$contentmd5];
}

// Check if a vote is submitted
if (isset($_POST['section']) && isset($_POST['content'])) {
    saveVote($_POST['section'], $_POST['content']);
}

// HTML and PHP to display the menu
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $app_title; ?></title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 0 auto;
    overflow: hidden;
}

h2 {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    margin-top: 20px;
}

ul {
    list-style: none;
    padding: 0;
}

ul li {
    background: #fff;
    border: 1px solid #ddd;
    margin-bottom: 5px;
    padding: 10px;
    position: relative;
}

.highest {
    background: #17b44e9c;
}

.exaequo {
    background: #b45c179c;
}

form {
    position: absolute;
    right: 10px;
    top: 10px;
}

input[type="button"] {
    border: 0;
    background-color: #5cb85c;
    color: white;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    margin-left: 20px;
}

input[type="button"]:hover {
    background-color: #4cae4c;
}

.winner {
    color: #d9534f;
    font-weight: bold;
}

.votecontents {
    width: 75%;
    margin-left: auto;
    margin-right: auto;
}

    </style>
</head>
<body>
    <div class="votecontents">
    <h1><?php echo $app_title; ?></h1>
    <?php foreach ($menu as $section => $contents):
        $sectionmd5 = md5($section);
       	if (!isset($_SESSION[$sectionmd5])) {
           $_SESSION[$sectionmd5] = array();
           $_SESSION[$sectionmd5]['voted'] = false;
        }

	$userVoted = $_SESSION[$sectionmd5]['voted'];

        $votes = getVotes();
        $highestvote = 0;
        $totalvotes = 0;
	$exaequo = 0;
        foreach ($contents as $content){
          $contentmd5 = md5($content);
          $n = getEntryVotes($contentmd5);
          $totalvotes += $n;
          if($n > $highestvote)
             $highestvote = $n;
        }

	foreach ($contents as $content){
	  $contentmd5 = md5($content);
          $n = getEntryVotes($contentmd5);
          if($n > 0 && $n == $highestvote){
             $exaequo++;
          }
	}

	?>
        <h2><?php echo htmlspecialchars($section); ?> (<?php echo $totalvotes; ?> votes)</h2>
        <ul>
            <?php foreach ($contents as $content):
		$contentmd5 = md5($content);
                $n = getEntryVotes($contentmd5);
                if($totalvotes > 0){
                   $percent = ($n * 100 / $totalvotes);
                }else{
                   $percent = 0;
                }
		$pct = number_format($percent, 0, ".", "");

                $showClass = false;
                $class = "highest";
		if($n > 0 && $n == $highestvote){
		   $showClass = true;
		}

		if($exaequo > 1 && $n == $highestvote){
	           $class = "exaequo";
		   $showClass = true;
		}

            ?>
                <li <?php if($showClass) echo " class=\"$class\""; ?>>
                    <div><?php echo htmlspecialchars($content); ?> (<?php echo $n; ?> votes / <?php echo $pct; ?>%)</div>
                    <?php if (!$userVoted): ?>
                        <form method="post">
                            <input type="hidden" name="section" value="<?php echo $sectionmd5; ?>">
                            <input type="hidden" name="content" value="<?php echo $contentmd5; ?>">
                            <input type="button" onclick="beforeSubmit(this.form);" name="vote" value="Voter">
 			    <!--<input type="submit" name="vote" value="Voter">-->
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        beforeSubmit = function(f){
           if (confirm('Sure, 100%?')){
               f.submit();
           }
        }
    </script>

    <div style="text-align: center; font-size: 0.9em;">
	Shitty code &copy; 2024 <a href="https://morve.us">Morveus</a> -- <a href="https://github.com/Morveus/shitty-vote">Github Repo</a><br />
	Proudly hosted on a very overkill 16 nodes cluster in my basement (84 cores, 400 GB RAM)
	</p>
    </div>
</body>
</html>
