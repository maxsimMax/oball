function parseData(data){
	var res = $.parseJSON(data);
    var template='<table class="resulttable">';
    template+='<caption>Результат:</caption>';
    for(var p in res){
    	var obj=res[p];
    	if (typeof(obj)=="object"){
    		template+='<tr>';
	    	for(var j in obj){
          var str=obj[j];
          if (str=='') str="null"
        	template+='<td>';
				  template+=str;
				  template+='</td>';
			}
    		template+='</tr>';
		}
    		else
    		{
    			template+='<td>';
				template+=obj;
				template+='</td>';
    		}
    	}
		
	template+='</table>';
	$('#content').empty();
	$('#content').append(template);
}

function getTournament(){
	var name=$('#get_tournament_input').val();
	var action='get_tournament';
	$.ajax({
              type: 'GET',
              url: 'index.php',
              data: {'action':action, 'par':{'name':name}},
              success: parseData,
              error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
}
function get_tournament_by_id(){
	var name=$('#get_tournament_by_id_input').val();
	var action='get_tournament_by_id';
	$.ajax({
              type: 'GET',
              url: 'index.php',
              data: {'action':action, 'par':{'id':name}},
              success: parseData,
              error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
}

function get_game(){
	var name=$('#get_game_input').val();
	var action='get_game';
	$.ajax({
              type: 'GET',
              url: 'index.php',
              data: {'action':action, 'par':{'id':name}},
              success: parseData,
              error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
}