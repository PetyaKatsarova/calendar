<?php
class Calendar{
   // PROPS
   private $daysInWeek = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');

   public function show()
   {    
        $content = '<section id="calendar">';
        $content .= '<i class="arrow up"></i>';        

        $count = 1;
        $prevMonth = date('M', strtotime('-1 month'));
        $nextMonth = date('M', strtotime('+1 month'));

        $content .= '<div class="mainContainer">';
        $content .= '<div class="months"><span class="prevMonth"><i>' . $prevMonth[0] . '</i><br><i>' . $prevMonth[1] . '<br></i>';
        $content .='<i>' . $prevMonth[2].'</i><br></span>';
        $content .= '<span>'.$nextMonth[0] . '</i><br><i>' . $nextMonth[1] . '<br></i><i>' . $nextMonth[2] . '<br></i></span></div>'; // months
        $content .= '<div class="cellsContainer">';
        $content .= '<span class="currmonth">'. date('M Y') . '</span>';
        $content .= '<ul class="days">';
        for($i=0; $i<count($this->daysInWeek); $i++){
           $content .= '<li>' .$this->daysInWeek[$i] . '</li>';
        }
        $content .= '</ul>';
        for ($timestamp = strtotime("-2 weeks Mon"); $timestamp <= strtotime("+2 weeks Sun"); $timestamp = strtotime("+1 day",$timestamp)) {
            if(($count % 7) === 1) $content .= '<div class="weeksUl">';
            if($timestamp === strtotime("Today")){
                $content .=  '<span class="dateCell today">' . date('d',$timestamp) . '</span>';
            }else{
                $content .=  '<span class="dateCell">' . date('d',$timestamp) . '</span>';
            }
            
            if(($count % 7) === 0) $content .= '</div>'; // weeksUL
            $count++;
        };
        $content .= '</div></div>'; // currMonthContainer/cellsContainer
        $content .= '<i class="arrow down"></i><br>';
        $content .= '<button type="button">Submit</button></section>';
        ob_start();
        ?>
        <script>
        const prevMonth = document.getElementsByClassName('prevMonth')[0];
        console.log(prevMonth);
        prevMonth.addEventListener('click', function(){
            <?php echo "<p>bla</p>"; ?>
        });
        </script>
        <?php
        $content .= ob_get_clean();
        return $content;
   }
   
}