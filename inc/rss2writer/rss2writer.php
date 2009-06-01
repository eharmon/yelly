<?php

/**
 *  Main file for {@link http://daniel.lorch.cc/projects/rss2writer/ RSS2Writer}
 *
 *  @version	$Id: rss2writer.php 45 2006-03-15 20:29:29Z daniel $
 */

/**
 *  Include Keith Devens' {@link http://keithdevens.com/software/phpxml PHP XML Library}
 */
require_once dirname(__FILE__) . '/xml.php';

/**
 *  RSS2Writer - A convenience class to generate RSS 2.0 feeds
 *
 *  RSS2Writer is very much inspired by (but otherwise not related to) Edd Dumbill's
 *  {@link http://usefulinc.com/rss/rsswriter/ RSSWriter} class. People who have used
 *  his class will notice that the API is similar, however, RSS2Writer is not a drop-in
 *  replacement. For example the {@link serialize()} method does not directly
 *  output the feed but returns it as a string (use {@link output()} to output).
 *
 *  Also, RSS2Writer creates RSS 2.0 feeds rather than RSS 1.0. And instead of echo-ing
 *  the raw XML code, I am building an array and serialize it afterwards to XML using
 *  Keith Deven's {@link http://keithdevens.com/software/phpxml PHP XML Library}. This
 *  makes the code much cleaner and shorter and in theory even would allow the data structure
 *  to be exported in another representation.
 *  
 *  Feeds generated with RSS2Writer have been tested against the
 *  {@link http://www.feedvalidator.org/ FEED Validator} and showed up as valid. I suggest
 *  you to also check against the FEED Validator once you have created your own feed.
 *
 *  Example:
 *
 *  <code>
 *  $rss = new RSS2Writer('http://myblog.example.com/');
 *  $rss->addItem(
 *    'http://myblog.example.com/article.php?id=123',
 *    'Interesting article!',
 *    '<b>Foo!</b><br />You totally gotta bar the foo.'
 *  );
 *  $rss->output();
 *  </code>
 *
 *  @package     RSS2Writer
 *  @author      Daniel Lorch <daniel@lorch.cc> 2006
 *  @version     0.01
 *  @example	 encoding.php How to make sure your umlauts get displayed correctly (examples/encoding.php)
 *  @example     date.php How to correctly format the dc:date (examples/date.php)
 *  @example	 simple.php Basic example (examples/simple.php)
 */
class RSS2Writer {

  /**
   *  Store data structure for RSS Feed. Will be serialized to XML when calling the
   *  {@link serialize()} method.
   * 
   *  @access private
   */
  var $data = array();

  /**
   *  Constructor. Create a new RSS2Writer.
   *
   *  Example:
   *
   *  <code>
   *  $rss = new RSS2Writer('http://myblog.example.com/', 'My blog', 'A blog about quuz');
   *  </code>
   *
   *  Additional meta data:
   *
   *  <code>
   *  $rss = new RSS2Writer('http://myblog.example.com/', 'My blog', 'A blog about quuz', array(
   *    'copyright' => 'Honey Inc',
   *    'generator' => 'My blog software 0.32',
   *    'dc:language' => 'en'
   *  ));
   *  </code>
   *
   *  See {@link useModule()} for an explanation of 'dc:' and which namespaces are bound by default.
   *
   *  @param string $uri		URI of your website
   *  @param string $title		Title or your website or this feed
   *  @param string $description	Description of your website or this feed
   *  @param mixed $meta		Array of key/value pairs for further meta data on this feed
   */
  function RSS2Writer($uri, $title=NULL, $description=NULL, $meta=array()) {
    $data['rss attr']['xmlns:dc'] = 'http://purl.org/dc/elements/1.1/';
    $data['rss attr']['xmlns:content'] = 'http://purl.org/rss/1.0/modules/content/';
    $data['rss attr']['version'] = '2.0';

    $data['rss']['channel']['title'] = $title;
    $data['rss']['channel']['link']  = $uri;
    $data['rss']['channel']['description'] = $description;
    $data['rss']['channel']['dc:date'] = gmdate('Y-m-d\TH:m:s\Z');

    foreach($meta as $key => $value) {
       $data['rss']['channel'][$key] = $value;
    }

    $data['rss']['channel']['item'] = array();

    $this->data = $data;
  }

  /**
   *  Add an item to the RSS feed
   *
   *  Example:
   *
   *  <code>
   *  $rss->addItem(
   *    'http://myblog.example.com/article.php?id=123',
   *    'Interesting article!',
   *    '<b>Foo!</b><br />You totally gotta bar the foo.'
   *  );
   *  </code>
   *
   *  Very often, you also want to add a date field. The correct formatting for the
   *  dc:date element is described in {@link http://www.w3.org/TR/NOTE-datetime Date and Time Formats}.
   *  The formatting string you can use for PHP's {@link http://php.net/gmdate date()} function is
   *  'Y-m-d\TH:m:s\Z' (in PHP5, this is defined in the DATE_W3C constant). If you are
   *  retrieving the date from a MySQL database, you can either use the MySQL function
   *  {@link http://dev.mysql.com/doc/mysql/en/date-and-time-functions.html UNIX_TIMESTAMP}
   *  to return a timestamp or otherwise convert it to a timestap using PHP's
   *  {@link http://php.net/strtotime strtotime()} function.
   *
   *  <code>
   *  // convert from MySQL's datetime to a timestamp
   * $timestamp = strtotime('2003-02-06 20:26:23');
   *
   *  $rss->addItem(
   *    'http://myblog.example.com/article.php?id=123',
   *    'Interesting article!',
   *    '<b>Foo!</b><br />You totally gotta bar the foo.',
   *    array(
   *      'dc:date' => date('Y-m-d\TH:m:s\Z', $timestamp)
   *    )
   *  );
   *  </code>
   *
   *  @param string $uri		URI of the article
   *  @param string $title		Title of the article
   *  @param string $content		Content of the article (HTML code)
   *  @param mixed $meta		Array of key/value pairs for further meta data
   */
  function addItem($uri, $title, $content, $meta=array()) {
    $item = array();
    $item['title'] = $title;
    $item['link']  = $uri;
    $item['content:encoded attr'] = array('xmlns' => 'http://www.w3.org/1999/xhtml');
    $item['content:encoded'] = $content;
    
    foreach($meta as $key => $value) {
      $item[$key] = $value;
    }

    array_push($this->data['rss']['channel']['item'], $item);
  }

  /**
   *  Bind another namespace
   *
   *  By default, the {@link http://dublincore.org/documents/dces/ Dublin Core Metadata Element Set} is
   *  bound to 'dc' and {@link http://purl.org/rss/1.0/modules/content/ RDF Site Summary 1.0 Modules: Content}
   *  to 'content'. So if you want to use the 'date' element defined in the dublin core metadata element set,
   *  you have to refer to it by 'dc' + colon + 'date' like this:
   *
   *  <code>
   *  $rss = new RSS2Writer('http://myblog.example.com/', 'My blog', 'A blog about quuz', array(
   *    'dc:creator' => 'Joe Brown',
   *    'dc:language' => 'en'
   *  ));
   *  </code>
   *
   *  The dc namespace has been bound like this:
   *
   *  <code>
   *  $rss->useModule('dc', 'http://purl.org/dc/elements/1.1/');
   *  </code>
   *
   *  @see http://rss-extensions.org/wiki/Main_Page
   *  @param string $prefix		Prefix to bind this namespace
   *  @param string $uri		URI to definition of this extension
   */
  function useModule($prefix, $uri) {
    $this->data['rss attr']['xmlns:' . $prefix] = $uri;
  }

  /**
   *  Serialize output as XML
   *
   *  Example:
   *
   *  <code>
   *  $fp = fopen('feed.xml', 'w')
   *    or die('Could not open feed.xml for writing');
   *  fputs($fp, $rss->serialize());
   *  fclose($fp);
   *  </code>
   *
   *  With specific encoding to allow german umlauts:
   *
   *  <code>
   *  $rss->serialize('ISO-8859-1');
   *  </code>
   *
   *  @param string $charset		Character set to use
   *  @return string			RSS-Feed as XML 
   */
  function serialize($charset=NULL) {
    return XML_serialize($this->data, $charset);
  }

  /**
   *  Output the serialized XML. Always prefer this over "echo $rss->serialize()", because
   *  output() will also set the HTTP-header for content-type to text/xml and also
   *  makes sure the encoding is passed along.
   *
   *  @param string $charset            Character set to use
   */
  function output($charset= NULL) {
    if(isset($charset)) {
      header('Content-type: text/xml; charset=' . $charset);
    } else {
      header('Content-type: text/xml');
    }
    echo $this->serialize($charset);
  }
}

?>
