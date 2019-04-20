<?php

/**
 *Gets a staff position and returns data
 *
 * @param $data
 */

 function getStaffPosition($position)
 {
     if ($position == "ZQO1")
     {
         return config('ganderstaff.ZQO1');
     }
     else if ($position == "ZQO2")
     {
        return config('ganderstaff.ZQO2');
     }
     else if ($position == "ZQO3")
     {
        return config('ganderstaff.ZQO3');
     }
     else if ($position == "ZQO4")
     {
        return config('ganderstaff.ZQO4');
     }
     else
     {
         return null;
     }
 }
