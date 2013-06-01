<div id="languageChooser">
<?php
if (array_key_exists('de', $config ['languages'])) {
echo '<div class="floatR"><img src="gfx/flagGe.png" class="floatL" alt="" /><a
  href="?language=de" title="german">german</a></div>';
}
if (array_key_exists('en', $config ['languages'])) {
echo '<div class="floatR"><img src="gfx/flagEn.png" class="floatL" alt="" /><a
  href="?language=en" title="english">english</a></div>';
 }
if (array_key_exists('pl', $config ['languages'])) {
echo '<div class="floatR"><img src="gfx/flagPl.png" class="floatL" alt="" /><a
  href="?language=pl" title="polski">polski</a></div>';
}
?>
</div>
<div class="clear"></div>

<div id="logoContainer">
<div class="width50 textL floatL"><img src="gfx/logo.png"
  alt="Profes Grupa Handlowa" /></div>
<div class="width50 textR floatL">
<form method="get" action="" name="searchForm"><input type="hidden"
  name="goSearch" value="true" /><input type="hidden" name="language"
  value="<?php echo $_REQUEST['language']; ?>" /><input type="text"
  size="25"
  value="<?php
  if (isset ( $_REQUEST ['searchText'] )) {
    echo $_REQUEST ['searchText'];
  } else {
    echo 'szukaj na stronie...';
  }
  ?>"
  onclick="$(this).val('');" onkeypress="checkSearchSubmit(event, this)"
  name="searchText" /></form>
</div>
<div class="clear"></div>
</div>

<div id="vImg">&nbsp;</div>

<div id="topContent">
<div class="rss floatR"><a href="<?php
echo rss::sFormatLink ();
?>"><img src="gfx/rss.png" alt="RSS" /></a></div>
	<?php
$tNews = new specialNews ( 'general' );
?>
	<h1><?php
echo $tNews->getTitle ();
?></h1>
<div class="topNewsContent"><?php
echo $tNews->getText ();
?></div>
<div class="textR"><a
  href="<?php
  echo news::sFormatLink ( $tNews->getNewsID () );
  ?>"><?php
  echo $t->get ( 'keepReading' );
  ?></a></div>
</div>

<?php

$tMenu = array ();

$tMenu ['about'] = new specialNews ( 'about' );
$tMenu ['information'] = new specialNews ( 'information' );
$tMenu ['product'] = new specialNews ( 'product' );
$tMenu ['collaboration'] = new specialNews ( 'collaboration' );
$tMenu ['contact'] = new specialNews ( 'contact' );

?>

<table class="menu" cellspacing="2">
  <tbody>
    <tr>
      <th>
      <h2><?php
      echo $tMenu ['about']->getTitle ( true );
      ?></h2>
      </th>
      <th>
      <h2><?php
      echo $tMenu ['information']->getTitle ( true );
      ?></h2>
      </th>
      <th>
      <h2><?php
      echo $tMenu ['product']->getTitle ( true, '?language=' . $_REQUEST ['language'] . '&amp;product=list' );
      ?></h2>
      </th>
      <th>
      <h2><?php
      echo $tMenu ['collaboration']->getTitle ( true );
      ?></h2>
      </th>
      <th>
      <h2><?php
      echo $tMenu ['contact']->getTitle ( true );
      ?></h2>
      </th>
    </tr>
    <tr>
      <td id="menu1"
        onclick="document.location='<?php
        echo news::sFormatLink ( $tMenu ['about']->getNewsID () );
        ?>';">
				<?php
    echo $tMenu ['about']->getShortText ();
    ?>
            </td>
      <td id="menu2"
        onclick="document.location='<?php
        echo news::sFormatLink ( $tMenu ['information']->getNewsID () );
        ?>';">
				<?php
    echo $tMenu ['information']->getShortText ();
    ?>
            </td>
      <td id="menu3"
        onclick="document.location='<?php
        echo '?language=' . $_REQUEST ['language'] . '&amp;product=list';
        ?>';">
				<?php
    echo $tMenu ['product']->getShortText ();
    ?>
            </td>
      <td id="menu4"
        onclick="document.location='<?php
        echo news::sFormatLink ( $tMenu ['collaboration']->getNewsID () );
        ?>';">
				<?php
    echo $tMenu ['collaboration']->getShortText ();
    ?>
            </td>
      <td id="menu5"
        onclick="document.location='<?php
        echo news::sFormatLink ( $tMenu ['contact']->getNewsID () );
        ?>';">
				<?php
    echo $tMenu ['contact']->getShortText ();
    ?>
            </td>
    </tr>
  </tbody>
</table>

<div id="contentBox">
<div id="rightMenu">
        <?php
        
        if (isset ( $_REQUEST ['product'] )) {
          /**
           * Lista podręczna towarów
           */
          $tItem = new productNavigator ( );
          echo $tItem;
        } else {
          
          /**
           * Lista newsów
           */
          $tItem = new newsNavigator ( );
          echo $tItem;
        }
        
        $tItem = new link ( );
        echo $tItem;
        
        $tItem = new userFrontend ( $_SESSION ['loggedUser'] );
        echo $tItem;
        
        ?>
    </div>

<div id="mainContent">
        <?php
        if (isset ( $_REQUEST ['product'] )) {
          /**
           * Wyświetlanie produktów
           */
          
          if (is_numeric ( $_REQUEST ['product'] )) {
            $tItem = new product ( $_REQUEST ['product'] );
            echo $tItem;
          } elseif ($_REQUEST ['product'] == 'groups') {
          
          } elseif ($_REQUEST ['product'] == 'list') {
            $tItem = new productPromoBox ( );
            echo $tItem;
          }
        } elseif (isset ( $_REQUEST ['news'] )) {
          
          /**
           * Wyświetlanie newsów
           */
          $tItem = new news ( $_REQUEST ['news'] );
          echo $tItem;
        } elseif (isset ( $_REQUEST ['newAccount'] )) {
          
          if ($_REQUEST ['newAccount'] != 'execute') {
            
            /**
             * Okno rejestracji
             */
            $tItem = new userFrontend ( );
            echo $tItem->add ( $_REQUEST );
          } else {
            
            /*
             * Zapisanie do bazy danych
             */
            $tItem = new userFrontend ( );
            $return = $tItem->addExe ( $_REQUEST );
            
            echo $return ['text'];
            
            if (! $return ['success']) {
              echo $tItem->add ( $_REQUEST );
            }
          
          }
        } elseif (isset ( $_REQUEST ['goEdit'] )) {
          
          if ($_REQUEST ['goEdit'] != 'execute') {
            
            /**
             * Okno rejestracji
             */
            $tItem = new userFrontend ( $_SESSION ['loggedUser'] );
            echo $tItem->edit ( $_REQUEST );
          } else {
            
            /*
             * Zapisanie do bazy danych
             */
            $tItem = new userFrontend ( );
            $return = $tItem->editExe ( $_REQUEST );
            
            echo $return ['text'];
            if (! $return ['success']) {
              echo $tItem->edit ( $_REQUEST );
            }
          }
        } elseif (isset ( $_REQUEST ['goSearch'] )) {
        
          /*
           * Wyszukiwanie
           */
          $tItem = new newsSearch($_REQUEST);
          echo $tItem;
          
        } else {
          /**
           * Wyświetl ostatniego newsa
           */
          $tItem = new lastNews ( );
          echo $tItem;
        }
        ?>
    </div>

</div>
<div id="footer">Copyright © 2009 Profes Grupa Handlowa, projekt i
wykonanie: Lynx-IT</div>