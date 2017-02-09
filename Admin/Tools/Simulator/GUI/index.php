<head>
    <title>Tests</title>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <style>
        #canvas { border: 1px solid black; margin-bottom: 30px; }
        #gui_info { border: 1px solid black; padding: 5px; }
        
        #canvas *:active { pointer-events: none; }
        
        .canvasHighlight { outline: 2px solid #2222FF; background-color: #9999FF !important; }
    </style>
</head>
<body>

<table>
  <tr>
    <td>
      <div id="canvas">
        <iframe id="canvas_iframe">
          <div id="mainDiv">i am the parent!
              <div id="sub1">i am sub1
                  <div id="sub2">and im the child <button onclick="alert('you turd');">Click me!</button></div>
              </div>
          </div>
        </iframe>
      </div>
    </td>
    <td>
      <div id="gui_interface">
        <h3> Interface </h3>
        <h4>
          <input type="button" value="Add" id="add_element_button" class="collectorButton" style="display:none">
          <input type="button" value="Edit" id="edit_element_button" class="collectorButton" style="display:none">
        </h4>
        <br>
        <div id="gui_interface_add_element">
          <table id="gui_interface_add_element_table">
            <tr>
              <td colspan="4"><h4>Stimuli</h5></td>
            </tr>
            <tr>
              <td><input type="button" value="Text"  class="collectorButton"></td>
              <td><input type="button" value="Image" class="collectorButton"></td>
              <td><input type="button" value="Audio" class="collectorButton"></td>
              <td><input type="button" value="Video" class="collectorButton"></td>
            </tr>
            <tr>
              <td colspan="4"><h4>Inputs</h5></td>
            </tr>
            <tr>
              <td><input type="button" value="Button" class="collectorButton"></td>
              <td><input type="button" value="Text" class="collectorButton">  </td>
              <td><input type="button" value="Number" class="collectorButton"></td>
              <td><input type="button" value="Date" class="collectorButton">  </td>
            </tr>
            <tr>
              <td colspan="4"><h4>Survey buttons</h5></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="button" value="Likert" class="collectorButton"></td>
              <td><input type="button" value="Radio" class="collectorButton"></td>
            </tr>
          </table>          
        </div>
        <div id="gui_interface_edit_element">
          <div id="gui_style">
            <h3 id="selected_element_id"></h3>
            <?= require("Interfaces/span_div_present.php") ?>          
          </div>
          <div id="gui_info"></div>        
        </div>
      </div>
    </td>
  </tr>
</table>

<script>

  $("#add_element_button").on("click",function(){
    $("#gui_interface_add_element").show();
    $("#gui_interface_edit_element").hide();
  });
  $("#edit_element_button").on("click",function(){
    $("#gui_interface_add_element").hide();
    $("#gui_interface_edit_element").show();
  });
   
    
  $("#gui_info").on("mouseenter", "*", function() {
    var this_class = $(this)[0].className;
    $("."+this_class).addClass("canvasHighlight");
      
  }).on("mouseleave", "*", function() {
    
    // fix - but secondary if I cannot access data within iframe //
    
    var this_class = $(this)[0].className;
    $("."+this_class).removeClass("canvasHighlight");
  }).on("click", "*" , function(){
      
    this_class = $(this)[0].className;
    this_class = this_class.replace("list_","");
    this_class = this_class.replace(" canvasHighlight","");
    var target = $("iFrame").contents().find("#"+this_class);
    
    temp_clob_trget = target;
    
    selected_element_id = target[0].id;      
    $("#selected_element_id").html(selected_element_id);
    $(target).removeClass("canvasHighlight");
    
    if(target.is("div")|target.is("span")){
      element_gui.span_or_div.process_text_style(target);  
    }
     
    // here is where the identification process is
    
    //console.dir($(target).css("color"));
    
    
  });;;
    
    
</script>

</body>