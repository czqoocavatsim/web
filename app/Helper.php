<?php

 /**
  *Gets a staff position and returns data.
  *
  * @param $data
  */
 function getStaffPosition($position)
 {
     if ($position == 'ZQO1') {
         return config('ganderstaff.ZQO1');
     } elseif ($position == 'ZQO2') {
         return config('ganderstaff.ZQO2');
     } elseif ($position == 'ZQO3') {
         return config('ganderstaff.ZQO3');
     } elseif ($position == 'ZQO4') {
         return config('ganderstaff.ZQO4');
     } else {
         return;
     }
 }
