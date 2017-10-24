
<?php require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
?>

<html>
    <head>
        <title>Facebook_Graph</title>
        <SCRIPT LANGUAGE="JavaScript">
            function ClearFun()
            {
                location.href = "http://cs-server.usc.edu:19192/CSCIHW6.php";  
            }
            function ShowLocation()
            {
                document.getElementById("loc").value=" ";
                document.getElementById("dis").value=" ";
               if(document.getElementById("choose").value=="place")
               {
                   document.getElementById("Location").style.visibility="visible";
               }
               else
               {
                   document.getElementById("Location").style.visibility="hidden";
               }
                   
            }
        </SCRIPT>
        <style>
            #maintext{
                background-color: antiquewhite;
                font-family:serif;
                border: 2px solid grey;
            }
            #recordtable,#recordtable th, #recordtable td {
                border: 2px solid black;
                background-color: floralwhite;
            }
            #recordtable th{width: 250px}
            #recordtable td{width: 250px}
            
            tr,td{
                border: 2px solid grey;
                background-color: antiquewhite;
                font-family:serif;
             }
        </style>
    </head>
    
    
     <body>
        <div id="maintext" align="center">
            <h1><I>Facebook Search</I></h1>
            <hr>
            <div align="left">
            <Form METHOD="POST" action="CSCIHW6.php" >
                <div style=" display:inline-block; width:60px">Keyword </div>
                <input TYPE="TEXT" SIZE=20 ID="keyword" name="keyword" value="<?php if(isset($_POST['keyword'])) echo $_POST['keyword']; else if(isset($_GET['keyword'])) echo $_GET['keyword']; ?>" required="true" ><br>
                <div style=" display:inline-block; width:60px">Type:</div>
                <SELECT ID="choose" name="choose" onchange="ShowLocation()" >
                <OPTION value="user" >Users</OPTION>
                <OPTION value="page" >Pages</OPTION>
                <OPTION value="event">Events</OPTION>
                <OPTION value="group">Groups</OPTION>
                <OPTION value="place">Places</OPTION>
                </SELECT>
                <BR/>
                <div id="Location" name="Location" style="visibility:hidden">
                    <div style=" display:inline-block; width:60px">Location</div>
                    <input TYPE=TEXT SIZE=20 ID='loc' name="loc" value="<?php if(isset($_POST['loc'])) echo $_POST['loc']; else if(isset($_GET['loc'])) echo $_GET['loc']; ?>" >
                    <div style=" display:inline-block; width:110px">Distance(meters)</div>
                    <input TYPE=TEXT SIZE=20 ID='dis' name="dis" value="<?php if(isset($_POST['dis'])) echo $_POST['dis']; else if(isset($_GET['dis'])) echo $_GET['dis']; ?>" >
                </div>
                <div style=" display:inline-block; width:65px"></div>
                <input TYPE="submit" name="Submit" VALUE="Search">
                <input TYPE="button" VALUE="Clear" name="Clear" onClick="ClearFun()" >
            </Form>
            </div> 
         </div>
         
         <!--show the result---->
        <?php if(isset($_POST["Submit"])): ?>
         <!--set the selected option --->
                <script type="text/javascript">
                        document.getElementById('choose').value = "<?php echo $_POST['choose'];?>";
                </script>
         <!--show the Location input --->
                <?php if($_POST["choose"]=="place"): ?>
                <script type="text/javascript">
                        document.getElementById('Location').style.visibility = "visible";
                </script>     
                <?php endif; ?>
         <div id="result" align="center"> 
            <?php
                $qv=$_POST["keyword"];
                $tv=$_POST["choose"];
                $locv=$_POST["loc"];
                $disv=$_POST["dis"];
                $gkey="AIzaSyAa7H7mN35FWcWRWG-WjjOLfcwtuhyndt4";
             $tokenv='EAAOXrplOyqMBADSJC4RfJMVhp02mhPhNZCJZA7tqMikyeXJtnF79aeeiFeoLbIAYvne7EfTSsolZAmD4NHb0pnIVOdtcwq6yHiPUukhISW8p7P91M7pzJ8DCb2s4ZAFcaFIJaMjZBShidlDAd8sAZBgjy6fC74dZBoZD';
             
             $fb = new Facebook\Facebook([
                            'app_id' => '1011201082313379',
                            'app_secret' => 'e264c699f818f589ac9fb5685c4f7603',
                            'default_graph_version' => 'v2.8',
                            'default_access_token' => 'EAAOXrplOyqMBADSJC4RfJMVhp02mhPhNZCJZA7tqMikyeXJtnF79aeeiFeoLbIAYvne7EfTSsolZAmD4NHb0pnIVOdtcwq6yHiPUukhISW8p7P91M7pzJ8DCb2s4ZAFcaFIJaMjZBShidlDAd8sAZBgjy6fC74dZBoZD',
            ]);
             
    //--begin try----*/ 
    try{
             
            if($tv=="place")
            {
                $cenurl="http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($locv)."&key=".$gkey;
                $cenjson=file_get_contents($cenurl);
                $ceninfo=json_decode($cenjson);
                ///////if fail/////
                if(isset($ceninfo->status)==false||strcmp($ceninfo->status,"ZERO_RESULTS")==0){
				          echo "PLEASE ENTER VALID ADDRESS";
				          return;
				}
                if(isset($ceninfo->results[0]))
                {
                    $latv=$ceninfo->results[0]->geometry->location->lat;
                    $lngv=$ceninfo->results[0]->geometry->location->lng;
                    $myurl="/search?q=".$qv."&type=".$tv."&center=".$latv.",".$lngv."&distance=".$distance."&fields=id,name,picture.width(700).height(700)";
                }
                else
                {
                    $myurl="/search?q=".$qv."&type=".$tv."&fields=id,name,picture.width(700).height(700)";
                }
            }
            elseif($tv=="event")
            {
                $myurl="/search?q=".$qv."&type=".$tv."&fields=id,name,picture.width(700).height(700),place";
            }
            else
            {
                $myurl="/search?q=".$qv."&type=".$tv."&fields=id,name,picture.width(700).height(700)";
            }
   
             $basejson=$fb->get($myurl);
             $baseinfo=json_decode($basejson->getBody());
            ?>
             
        <!--show the basic content---->
        <?php if(isset($baseinfo->data)&&count($baseinfo->data)>0): ?>
          <table id="recordtable">
                <tr>
                    <th>Profile Photo</th>
                    <th>Name</th>
                    <th><?php if($tv=="event") echo "Place"; else echo "Details"; ?></th>
                </tr>   
        <?php  for($i=0; $i<count($baseinfo->data); $i++): ?> 
              <tr>
                <td><img src="<?php echo $baseinfo->data[$i]->picture->data->url; ?>" width="35" height="40" onclick= "window.open('<?php echo $baseinfo->data[$i]->picture->data->url; ?>','_blank')" >
                </td>
                 
                <td><?php echo $baseinfo->data[$i]->name; ?></td>
                  
                 <!--show the Detail link or the Event--EVENT--> 
                  <?php if($tv=="event"): ?>
                  <td>
                      <?php 
                          if(isset($baseinfo->data[$i]->place->name)) 
                              echo $baseinfo->data[$i]->place->name; 
                      ?>
                  </td>
                 <!--show the Detail link or the Event--DTAIL--> 
                  <?php else: ?>
                  <td><a href="?detid=<?php echo $baseinfo->data[$i]->id; ?>&keyword=<?php echo $_POST["keyword"]; ?>&choose=<?php echo $_POST["choose"]; ?>&loc=<?php echo $_POST["loc"]; ?>&dis=<?php echo $_POST["dis"]; ?>">Details</a></td>
                 <!--end the choice--->
                  <?php endif; ?>
              </tr>
        <?php endfor; ?>  
            </table>
        <!--show NO content---->
        <?php else: ?>
            <div style="background-color: cornsilk; 
                font-family:serif; border: 5px solid grey;
                padding: 15px; font-size:150%">No Records has been found</div>
               
        <!--END CONTENT---->    
        <?php endif; ?> 
        <!--END POST----> 
        </div>
         
         <?php
    }catch(Facebook\Exceptions\FacebookResponseException $e) 
         {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
         }
         ?>
         
         
         
        <?php else: ;?>      
         
                  
         <!--if Detail click---->    
         <?php if(isset($_GET["detid"])): ?>
         <!--set the selected option ---> 
         <script type="text/javascript">
                        document.getElementById('choose').value = "<?php echo $_GET['choose'];?>";
         </script>
         <!--show the location input ---> 
         <?php if($_GET["choose"]=="place"): ?>
         <script type="text/javascript">
                        document.getElementById('Location').style.visibility = "visible";
         </script>     
        <?php endif; ?>
         
         
         <?php
         $fb = new Facebook\Facebook([
                            'app_id' => '1011201082313379',
                            'app_secret' => 'e264c699f818f589ac9fb5685c4f7603',
                            'default_graph_version' => 'v2.8',
                            'default_access_token' => 'EAAOXrplOyqMBADSJC4RfJMVhp02mhPhNZCJZA7tqMikyeXJtnF79aeeiFeoLbIAYvne7EfTSsolZAmD4NHb0pnIVOdtcwq6yHiPUukhISW8p7P91M7pzJ8DCb2s4ZAFcaFIJaMjZBShidlDAd8sAZBgjy6fC74dZBoZD',
            ]);
         
         $deturl="/".$_GET["detid"]."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)";
         
  try{         
         $detjson=$fb->get($deturl);
         $detinfo=json_decode($detjson->getBody()); 
         ?>
         
         <center>
         <!-- set albums --->
         <br/>
         <div id="albtitle" style=" border: 1px solid grey; border-collapse: collapse">
         <?php if((count($detinfo)==0)||(isset($detinfo->albums)==false)): ?>
                <p>No Albums has been found</p>
         <?php else: ?>
                <a href="#" onclick="showtabAlbums()">Albums</a>
         </div>
             <table id="tabAlbums" style="display: none; border: 1px solid grey; border-collapse: collapse; align:center " >
                <?php for($j=0; $j<count($detinfo->albums->data); $j++): ?>
                 
                <?php if(isset($detinfo->albums->data[$j]->photos)==false||count($detinfo->albums->data[$j]->photos->data)==0): ?>
                    <tr ><td><?php echo $detinfo->albums->data[$j]->name; ?></td></tr>
                <?php else: ?>
                    <tr ><td><a href="#" onclick="showPic('<?php echo "pic".$j; ?>')"> <?php echo $detinfo->albums->data[$j]->name; ?></a></td></tr>
                            <tr id="pic<?php echo $j;?>" style="display: none">
                                      
                                        <?php for($k=0;$k<count($detinfo->albums->data[$j]->photos->data);$k++): ?>
                                        <td>
                                         <?php 
                                            $picurl="/".$detinfo->albums->data[$j]->photos->data[$k]->id."/picture?redirect=0"; 
                                            $picjson=$fb->get($picurl);
                                            $picinfo=json_decode($picjson->getBody());
                                        ?>
                                         <img src="<?php echo $detinfo->albums->data[$j]->photos->data[$k]->picture; ?>" width="80" height="80" onclick="window.open('<?php echo $picinfo->data->url; ?>','_blank')" >
                                            
                                        </td>
                                        <?php endfor; ?>
                            </tr>            
                        <?php endif; ?>
                   <?php endfor; ?>                 
                </table>        
        <!--endif Albums show---->
        <?php endif; ?>     

        
         
         <!-- set Posts --->
        <br/>
         <div id="postitle" style=" border: 1px solid grey; border-collapse: collapse ">
             <?php if(count($detinfo)==0||isset($detinfo->posts)==false): ?>
                    <p>No Posts has been found</p>
             <?php else: ?>
             <a href="#" onclick="showtabPosts()" >Posts</a> 
         </div>
        <br/>
             <table id="tabPosts" style="display: none; border: 1px solid grey; border-collapse: collapse; align:center" >
                    <tr><th>Message</th></tr>
                    <?php for($j=0;$j<count($detinfo->posts->data);$j++): ?>
                        <?php if(isset($detinfo->posts->data[$j]->message)): ?>
                        <tr><td><?php echo $detinfo->posts->data[$j]->message; ?></td></tr>
                        <?php endif; ?>
                    <?php endfor; ?>
             </table> 
             <?php endif; ?> 
         
</center>   
         <!--end try---->  
    <?php
         }
    catch(Facebook\Exceptions\FacebookResponseException $e) 
         {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
         }
    ?> 
        <!--end catch----> 
        <!--endif Detail click---->  
        <?php endif; ?>    
        <?php endif; ?>
         
     
         
         <script>
    function showtabPosts() {
    document.getElementById("tabPosts").style.display="block";
    document.getElementById("tabAlbums").style.display="none"; }
    function showtabAlbums() {
    document.getElementById("tabPosts").style.display="none";
    document.getElementById("tabAlbums").style.display="block"; }
    function showPic(picIndex){
    if(document.getElementById(picIndex).style.display=="none")
        document.getElementById(picIndex).style.display="block";
    else
        document.getElementById(picIndex).style.display="none";
    }      
         </script>
    </body>
</html>

