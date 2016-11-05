<style>
  #column_list > div { cursor: pointer; }
  #column_list > div.selected_column { font-weight: bold; }
  .column_edit_quick_analysis { display: none; }
  .column_edit_quick_analysis.allow_quick_analysis { display: block; }
  .column_name { color : #600; font-weight: bold; }
  #analysis_table td{
    vertical-align:top;    
  }
  
</style>

<div id="analysis_area">
  <table id="analysis_table" style="width:100%">
    <tr>
       <td id="variable_list_td">
        <div id="variable_list_area">
          <h3>Columns</h3>
          <div id="column_list"></div>
          <h3>Local Variables</h3>
          <div id="variable_list">
          </div>
        </div>
      </td>
      <td>
        <div id="td_left_col"> 
          <span>
            <button type="button" class="gui_script_button collectorButton" id="gui_button" value="gui">GUI</button>
            <button type="button" class="gui_script_button collectorButton" id="script_button" value="script">Script</button>
          </span>
          
          <script>
              
            $(".gui_script_button").on("click",function(){
              $(".gui_script_areas").hide();
              $("#"+this.value+"_area").show();              
            });
          </script>
          
          <div id="gui_area" class="gui_script_areas"><?= require("gui.php") ?></div>
          
          <div id="script_area" class="gui_script_areas">
            <textarea id="javascript_script"></textarea>
            <button type="button" class="collectorButton" id="javascript_script_run_button">run</button>
            <button type="button" class="collectorButton" id="javascript_script_clear_output_run_button">clear output and run</button>
            <br>
            <textarea id="single_line_console" rows=1></textarea>
            <div id="console_area"></div>
          </div>
          
        </div>
      </td>
  <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script><!-- for jquery highlighting !-->

  <script>

    function script_to_console(this_script){
      
      try{                  
        this_script_eval= eval(this_script);
        console_log+="<pre class='succesfull_code'>"+this_script+"</pre>"+
                     "<pre class='succesfull_eval'>"+this_script_eval+"</pre>";
        
        update_var_list();
            
      } catch(err){
        console_log+="<pre class='error_code'>"+err+"</pre>";
      }
      
      $("#console_area").html(console_log);
    }
  
  
  //default variables
		var ctrPressed;
		var map = []; // Or you could call it "key"
    
    onkeydown = function(event){
      if(event.which == 13){
//        var input_console = $("#single_line_console");

        if (event.ctrlKey || event.metaKey) { // if SHIFT ENTER
          
          var textComponent = document.getElementById('javascript_script');
          ctrPressed=0;
          var selectedText;
          // IE version
          if (document.selection != undefined){
            textComponent.focus();
            var sel = document.selection.createRange();
            selectedText = sel.text;
          }
          
          // Mozilla version
          else if (textComponent.selectionStart !=  undefined){
            var startPos = textComponent.selectionStart;
            var endPos = textComponent.selectionEnd;
            selectedText = textComponent.value.substring(startPos, endPos)
          }
          
          script_to_console(selectedText);
        }
      }
    }
    
  
    $("#single_line_console").keyup(function(event){
      
      if(event.which == 13){
        var input_console = $("#single_line_console");

        if (event.shiftKey || event.metaKey) { // if SHIFT ENTER
    
          event.preventDefault();
          
          var current_rows = input_console.prop("rows");
          input_console.prop("rows",current_rows+1);
          
        } else { // no shift key
        
          var this_script = $("#single_line_console").val();

          script_to_console(this_script);
          $("#single_line_console").val("");
          input_console.prop("rows",1);
        }
      }

    });
  
  
  
    $("#column_list").on("click", "div", function() {
      var clicked_column = $(this);
      if (clicked_column.hasClass("selected_column")) {
        clicked_column.removeClass("selected_column");
        remove_column_menu();
        return;
      }
      
      $(".selected_column").removeClass("selected_column");
      clicked_column.addClass("selected_column");
      create_column_edit_menu(this);
    });
    
    $("body").on("click", function(e) {
      if ($(e.target).closest(".column_menu").length < 1) {
        remove_column_menu();
      };
    });
    
    function remove_column_menu() {
      $(".column_menu:not(.appearing)").hide(100, function() {
        $(".selected_column").removeClass("selected_column");
        $(this).remove();
      });
    }
    
    function get_offset(elem) {
      var body_coords = document.body.getBoundingClientRect();
      var elem_coords = elem.getBoundingClientRect();
      
      return {
        top:    elem_coords.top    - body_coords.top,
        left:   elem_coords.left   - body_coords.left,
        right:  elem_coords.right  - body_coords.left,
        bottom: elem_coords.bottom - body_coords.top,
      }
    }
    
    function create_column_edit_menu(column) {
      $(".column_menu").remove();
      
      var column_name = column.innerHTML;
      
      if (typeof data_by_columns[column_name][0] === "number") {
        var type = "number";
      } else {
        var type = "string";
      }
      
      var offset = get_offset(column);
      
      var menu = $("<div>");
      
      menu.addClass("column_menu").addClass("appearing");
      
      menu.css({
        backgroundColor: "#CCC",
        border: "2px solid #444",
        position: "absolute",
        top:  offset.top,
        left: offset.right + 10,
        padding: "10px",
        width: "350px",
        height: "300px",
        display: "none"
      });
      
      menu.html(get_column_menu_contents(type));
      
      $("body").append(menu);
      
      menu.show(150, function() {
        $(this).removeClass("appearing");
      });
    }
    
    function get_column_menu_contents(type) {
      if (type === "number") {
        var format_number_val = " checked";
        var format_string_val = "";
        var quick_analysis_class = "allow_quick_analysis";
      } else {
        var format_number_val = "";
        var format_string_val = " checked";
        var quick_analysis_class = "";
      }
      
      var contents = "";
      contents += "Column: " + $(".selected_column").html();
      
      var format_choice = "<div class='column_edit_choose_format'>";
      format_choice += "<div>Column type:</div>";
      var choice_start = "<div><label><input type='radio' name='column_format' value='";
      format_choice += choice_start+"number' "+format_number_val+"> Number</label></div>";
      format_choice += choice_start+"string' "+format_string_val+"> String</label></div>";
      format_choice += "</div>";
      
      contents += format_choice;
      var quick_analysis = "<div class='column_edit_quick_analysis "+quick_analysis_class+"'>";
      quick_analysis += "<button type='button' class='column_quick_mean'>Calculate Mean</button>";
      quick_analysis += "<button type='button' class='column_quick_std' >Calculate St.Dev</button>";
      quick_analysis += "</div>";
      
      contents += quick_analysis;
      
      return contents;
    }
    
    $("body").on("change", "input[name='column_format']", function() {
      var column_name = $(".selected_column").html();
      
      if (this.value === "number") {
        $(".column_edit_quick_analysis").addClass("allow_quick_analysis");
        
        data_by_columns[column_name] = data_by_columns[column_name].map(function(val) {
          return parseFloat(val);
        });
      } else {
        $(".column_edit_quick_analysis").removeClass("allow_quick_analysis");
        
        data_by_columns[column_name] = data_by_columns[column_name].map(function(val) {
          return "" + val;
        });
      }
    });
    
    $("body").on("click", ".column_quick_mean", function() {
        var col_name = $(".selected_column").html();
        var col_vals = data_by_columns[col_name];
        
        $("#output_area").append(
          '<div><span class="column_name">'
          + col_name
          + '</span> mean: ' 
          + jStat.mean(col_vals)
          + '</div>'
        );
    });
    
    $("body").on("click", ".column_quick_std", function() {
        var col_name = $(".selected_column").html();
        var col_vals = data_by_columns[col_name];
        $("#output_area").append(
          '<div><span class="column_name">'
          + col_name
          + '</span> st.dev: ' 
          + jStat.stdev(col_vals, true)
          + '</div>'
        );
    });
  
    var Defined_Vars = [];
    
    function save_var(name, val) {
        window["Defined_Vars"][name] = val;
    }
    
    
    function report(val,user_label) {
     
      $("#output_area").append("<div>" + user_label+ "<br>" + val + "</div><hr style='background-color:black'></hr>");
     
    }
    
    function update_var_list() {
        var var_list = [];
        
        for (var defined_var in window["Defined_Vars"]) {
            var_list.push(defined_var);
        }
        
        $("#variable_list").html(
            "<div>" + var_list.join("</div><div>") + "</div>"
        );
    }
    
    // running javascript_script
    console_log = '';
    
    
    $("#javascript_script_clear_output_run_button").on("click",function(){
      
      $("#output_area").html("");
      
      $("#javascript_script_run_button").click();
    });
    
    
    $("#javascript_script_run_button").on("click",function(){
      
      this_script = $("#javascript_script").val();
      
      script_to_console(this_script);

      return;
   
      
    });  
   
    
    
    var analysis_json = {
      javascript_script:''
    };
    
    $("#javascript_script").on("input",function(){
      analysis_json['javascript_script']=$("#javascript_script").val();
      update_json();
    });
    
    function update_json(){
      $("#analysis_json_textarea").val(JSON.stringify(analysis_json));
    }
    
  </script>
  <td>
    <div id="right_col">
      <span>
        <button class="output_toolbox_buttons collectorButton" type="button" value="output">Output</button>
        <button class="output_toolbox_buttons collectorButton" type="button" value="toolbox">Toolbox</button>
      </span>
      
      <script>
        $(".output_toolbox_buttons").on("click",function(){
          $(".output_toolbox_areas").hide();
          $("#"+this.value+"_area").show();
        });
      </script>
      
      <div id="output_area" class="output_toolbox_areas">     
      </div>
      <div id="toolbox_area" class="output_toolbox_areas">
      
      <?php require("Toolboxes.php"); ?>
      
      </div>      

    </div>
  </td>
</div>
