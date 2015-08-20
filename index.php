<?php

// for debugging .. Using KINT
// http://raveren.github.io/kint/#installation

require 'kint/Kint.class.php';
//include("/kint/Kint.class.php");

/* KINT examples
Kint::dump( $_SERVER );
// or, even easier, use a shorthand:
d( $_SERVER );
// or, to seize execution after dumping use dd();
dd( $_SERVER ); // same as d( $_SERVER ); die;


// to see trace:
Kint::trace();
// or pass 1 to a dumper function
Kint::dump( 1 );


// to disable all output
Kint::enabled(false);
// further calls, this one included, will not yield any output
d('Get off my lawn!'); // no effect

*/

Kint::enabled(true);
//Kint::trace();
include("application/header.php");

// Kint::dump( $_SESSION );


///// copy these two files for displaying navigation/////
$inner_display_page = 'home';

Navigation($inner_display_page);
//////////////


$relevant_data = array("DAY" => "Today", "WEEK" => "Last Week", "MONTH" => "Last Month");
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<div class="slider">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="item active"> <img src="<?php echo BASE_IMAGES_URL ?>slide1.jpg" alt="" class="img-responsive">
        <div class="container">
          <div class="carousel-caption slide1">
            <h1>
              <?php echo HOME_SLIDER_TITLE1 ?>
            </h1>
            <p>
              <?php echo HOME_SLIDER_CONTENT1 ?>
            </p>
            <p><a class="btn btn-lg btn-primary" href="#" role="button">
                <?php echo HOME_SLIDER_BUTTON_TEXT1 ?>
              </a></p>
          </div>
        </div>
      </div>
      <div class="item"> <img src="<?php echo BASE_IMAGES_URL ?>slide2.jpg" alt="" class="img-responsive">
        <div class="container">
          <div class="carousel-caption slide2">
            <h1>
              <?php echo HOME_SLIDER_TITLE2 ?>
            </h1>
            <p>
              <?php echo HOME_SLIDER_CONTENT2 ?>
            </p>
            <p><a class="btn btn-lg btn-primary" href="#" role="button">
                <?php echo HOME_SLIDER_BUTTON_TEXT2 ?>
              </a></p>
          </div>
        </div>
      </div>
      <div class="item"> <img src="<?php echo BASE_IMAGES_URL ?>slide3.jpg" alt="" class="img-responsive">
        <div class="container">
          <div class="carousel-caption slide3">
            <h1>
              <?php echo HOME_SLIDER_TITLE3 ?>
            </h1>
            <p>
              <?php echo HOME_SLIDER_CONTENT3 ?>
            </p>
            <p><a class="btn btn-lg btn-primary" href="#" role="button">
                <?php echo HOME_SLIDER_BUTTON_TEXT3 ?>
              </a></p>
          </div>
        </div>
      </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span></a> </div>
  <!-- /.carousel -->
</div>
<div class="jumbotron search-form">
  <div class="container">
    <div class="row row-offcanvas row-offcanvas-right">
      <div class="col-12 col-sm-12 col-lg-12">
        <h3>
          <?php echo SEARCH_PROJECTS ?>
          :</h3>
        <form action="">
          <input type="text" placeholder="<?php echo PROJECT_NAME_PLACEHOLDER ?>" name="projectName" id="projectName" class="input-bg form-control" value="" />
          <input type="text" placeholder="<?php echo PROJECT_DESC_PLACEHOLDER ?>" name="projectDesc" id="projectDesc" class="input-bg form-control" value="" />
          <input type="hidden"  name="category" id="category" class="input-bg form-control"  value="">
          <div class="btn-group select">
            <button type="button" class="btn btn-danger main-select" id="selected_category_value">------<?php echo PROJECT_CATEGORY ?>------</button>
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only"><?php echo TOGGLE_NAVIGATION ?></span> </button>
            <ul class="dropdown-menu" role="menu" id="select_category">
              <?php
              //  echo "<br><br><br><br><br><pre>".$All_Dropdown_Array['project_categories'];
              $query = "select * from {$All_Dropdown_Array['project_categories']}";
              $result = mysql_query($query);
              while ($category = mysql_fetch_array($result))
              {
                ?>
                <li data-value="<?php echo $category['project_category_id'] ?>"> <a>
                    <?php echo $category['project_categeory_name'] ?>
                  </a> </li>
                <?php
              }
              ?>
            </ul>
          </div>
          <input type="hidden"  name="relevant" id="relevant" class="input-bg form-control"  value="">
          <div class="btn-group select">
            <button type="button" class="btn btn-danger main-select" id="selected_joined_value">------<?php echo CREATED ?>------</button>
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only"><?php echo TOGGLE_NAVIGATION ?></span> </button>
            <ul class="dropdown-menu" role="menu" id="select_joined">
              <li data-value="DAY"><a>
                  <?php echo TODAY ?>
                </a></li>
              <li data-value="WEEK"><a>
                  <?php echo LAST_WEEK ?>
                </a></li>
              <li data-value="MONTH"><a>
                  <?php echo LAST_MONTH ?>
                </a></li>
            </ul>
          </div>
          <input type="button" value="<?php echo SEARCH ?>" name="submitSearch" class="btn btn-primary" id="search_popular_projects">
          <input type="reset" value="<?php echo RESET ?>" name="resetSearch" class="btn btn-primary" id="reset_search_popular_projects">
        </form>
      </div>
    </div>
    <!--/row-->
  </div>
  <!--/.container-->
</div>
<div class="jumbotron projects">
  <div class="container">
    <div class="row">
      <div class="col-6 col-sm-6 col-lg-6 sortby">
        <h3>
          <?php echo SORT_BY ?>
        </h3>
        <span>
          <div class="btn-group select2">
            <button type="button" class="btn btn-danger main-select2" id="sort_popular_users_value">---<?php echo SELECT ?>----</button>
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only"><?php echo TOGGLE_NAVIGATION ?></span> </button>
            <ul class="dropdown-menu" role="menu" id="sort_popular_users">
              <li data-value="Alpha"><a>
                  <?php echo ALPHABETICALL ?>
                </a></li>
              <li data-value="Date"><a>
                  <?php echo PROJECT_CREATED_DATE ?>
                </a></li>
              <li data-value="Relevance"><a>
                  <?php echo RELEVANCE ?>
                </a></li>
            </ul>
          </div>
        </span> <span>
          <input type="hidden"  name="sort_hidden_value" id="sort_hidden_value" class="input-bg form-control"  value="">
          <input type="button" id="sortList" value="<?php echo SORT ?>" name="sortList" class="btn btn-primary">
        </span> </div>
      <div class="col-6 col-sm-6 col-lg-6 grid-type">
        <span id="listView" class="glyphicon glyphicon-align-right"></span>
        <span id="boxView" class="glyphicon glyphicon-th-large"></span>
        <span id="thumbView" class="glyphicon glyphicon-th-list"></span>
        <span></span>
      </div>
    </div>
    <!--Loading Popular Users from ajax call -->
    <div class="row" id="popular_users" ></div>
    <div id="pagination_div" style="float: left;text-align: center;"></div>
  </div>
</div>
<div class="jumbotron bottom-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3  data-scroll-reveal="enter top over 1s and move 100px">
          <?php echo HOME_FOOTER_TITLE ?>
        </h3>
        <p>
          <?php echo HOME_FOOTER_CONTENT ?>
        </p>
        <p  data-scroll-reveal="enter bottom over 1s and move 100px"><a class="btn btn-lg btn-primary" href="#" role="button">
            <?php echo HOME_FOOTER_BUTTON_TEXT ?>
          </a></p>
      </div>
    </div>
  </div>
</div>
<div id="copy_popular" style="display: none;">
  <?php
  $_REQUEST['tab'] = $projectTblArray['tableAlias'];
  $_REQUEST['pname'] = $projectTblArray['tableAlias'];
  include("socprox3.0/views/table.view.php");
  d("loaded table.view");
  ?>
</div>
<div id="list-view-hold" style="display: none;"></div>
<!--<div id="newlist-view-hold" style="display: none;"></div>
<div id="box-view-hold" style="display: none;"></div>
<div id="thumb-view-hold" style="display: none;"></div>-->
<script>

</script>

<?php include("application/footer.php"); ?>
