<?php
$totalPage = 5;
$curPage = 1;
$startPage = ($curPage < 5)? 1 : $curPage - 4;
$endPage = 8 + $startPage;
$endPage = ($totalPage < $endPage) ? $totalPage : $endPage;
$diff = $startPage - $endPage + 8;
$startPage -= ($startPage - $diff > 0) ? $diff : 0;

if ($startPage > 1) echo " First ... ";
for($i=$startPage; $i<=$endPage; $i++) echo " {$i} ";
if ($endPage < $totalPage) echo " ... Last ";
?>