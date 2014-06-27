<?php

include "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

include "index.html.php";

?>