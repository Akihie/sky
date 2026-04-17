<?php
$this->need("header.php");
echo "HEADER OK<br>";
echo "Title: " . $this->title() . "<br>";
echo "Content: " . substr(strip_tags($this->content()), 0, 50) . "<br>";
echo "FOOTER OK<br>";
$this->need("footer.php");
