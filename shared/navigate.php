			<div class="navigate">
            <?php
            if (!isset($nav_params)){
	            $nav_params = "";
            }
            $count = $_SESSION[$count_name];

            $limiti = linkedPages($offset, $limit, $pagine);
            if ($pagine > 10) {
            	// aggiungo i link fissi per la prima e l'ultima pagina
            	$offset_last = $count - ($count%$limit);
            	print ("<a href='".$link."?offset=0&second=1".$nav_params."' style='margin-right: 15px; text-decoration: none; font-weight: bold'>&lt;&lt;</a>");
            }
            if($offset > 0)
                print("<a style='margin-right: 5px; text-decoration: none' href='".$link."?offset=".($offset - $limit)."&second=1".$nav_params."'>");
            else
                print("<span style='margin-right: 5px; color: #BDBDCF;'>");
            print("&lt;");
            if($offset > 0)
                print("</a>\n");
            else
                print("</span>");
            //print("&nbsp;&nbsp;&nbsp;");
            for($c = $limiti[0]; $c <= $limiti[1]; $c++){
                if($c <= $pagine){
                    if($c != ($offset / $limit) + 1 )
                        print("<a style='margin-right: 5px; margin-left: 5px; text-decoration: none' href='".$link."?offset=".(($c -1) * $limit)."&second=1".$nav_params."' style=\"font-weight: normal;\">&nbsp;$c&nbsp;");
                    else
                        print("<span style='margin-right: 5px; margin-left: 5px'>[$c]");

                    if($c != ($offset / $limit) + 1 )
                        print("</a>");
                    else
                        print("</span>");
                    if($c < $limiti[1])
                        print("<span>|</span>");
                }
            }
            print("&nbsp;&nbsp;");
            if(($count - $offset) > $limit)
                print("<a style='margin-left: 5px; text-decoration: none' href='".$link."?offset=".($offset + $limit)."&second=1".$nav_params."' style='font-weight: normal;'>");
            else
                print("<span style='color: #BDBDCF;'>");
            print("&gt;");
            if(($count - $offset) > $limit)
                print("</a>");
            else
                print("</span>");
            if ($pagine > 10) {
            	print ("<a href='".$link."?offset=".$offset_last."&second=1".$nav_params."' style='margin-left: 15px; text-decoration: none; font-weight: bold'>&gt;&gt;</a>");
            }
            ?>
			</div>
