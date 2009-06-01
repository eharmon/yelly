<?
for($i=0;$i<count($data['feeds']);$i++) {
	$rss = fetch_rss( $data['feeds'][$i] );
	if (!$rss) continue;
	$out .= '<outline text="' . str_replace("&","&amp;",$rss->channel['title']) . 
		'" htmlUrl="' . str_replace('&','&amp;',$rss->channel['link']) . 
		'" xmlUrl="' . str_replace('&','&amp;',$data['feeds'][$i]) . 
		'" />' . "\n" ;
	$rss = null;
}

$out = '<?xml version="1.0" encoding="UTF-8"?>
<opml version="1.1">
	<head>
		<title>' . $SITETITLE . '</title>
		<dateCreated>' . date('r') . '</dateCreated>
		<dateModified>' . date('r') . '</dateModified>
		<ownerName>' . $OWNERNAME . '</ownerName>
		<ownerEmail>' . $OWNEREMAIL . '</ownerEmail>

	</head>
	
	<body>
' . $out . "</body></opml>" ;

$fp = fopen('./cache/opml.xml','w') ; 
fputs($fp,$out) ;
fclose($fp) ;
?>
