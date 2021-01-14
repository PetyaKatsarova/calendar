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
        $content .= '<div class="months"><p class="prevMonth">' . $prevMonth . '</p>';
        $content .= '<p class="nextMonth">' . $nextMonth . '</p></div>'; // months
        // $content .= '<div class="months"><p class="prevMonth"><i>' . $prevMonth[0] . '</i><br><i>' . $prevMonth[1] . '</i></br><i>' . $prevMonth[2] . '</i><br></p>';
        // $content .= '<p class="nextMonth"><i>'.$nextMonth[0] . '</i><br><i>' . $nextMonth[1] . '</i><br><i>' . $nextMonth[2] . '</i><br></p></div>'; // months
        $content .= '<div class="cellsContainer">';
        $content .= '<span class="currmonth">'. date('M Y') . '</span>';
        $content .= '<ul class="days">';
        for($i=0; $i<count($this->daysInWeek); $i++){
           $content .= '<li>' .$this->daysInWeek[$i] . '</li>';
        }
        $content .= '</ul>';


        for ($timestamp = strtotime("-2 weeks Mon"); $timestamp <= strtotime("+2 weeks Sun"); $timestamp = strtotime("+1 day",$timestamp)) {
            if(($count % 7) === 1) $content .= '<div class="weeksUl" data-monday="'.date('Y M d',$timestamp).'">';
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
        // // get all php els to sabstitude content in js
        const prevMonth = document.getElementsByClassName('prevMonth')[0];
        const nextMonth = document.getElementsByClassName('nextMonth')[0];

        const section = document.querySelector('#calendar')
        const arrowUp = document.getElementsByClassName('up')[0]
        const arrowDown = document.getElementsByClassName('down')[0]
        const mainContainer = document.getElementsByClassName('mainContainer')[0]

        const months = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        let calendar = document.getElementById('calendar')  

        const month = document.getElementsByClassName('months')[0]
        const cellsContainer = document.getElementsByClassName('cellsContainer')[0]
        let weeksUl = document.getElementsByClassName('weeksUl')
        let dateCell = document.getElementsByClassName('dateCell')
                
        prevMonth.addEventListener('click', function(){
    
            const currmonth = document.getElementsByClassName('currmonth')[0]
            let firstMonday = calendar.querySelectorAll('.weeksUl')[0]

            let date = new Date(firstMonday.getAttribute("data-monday")); // every monday
            let monthNum = date.getMonth();
            let currMonth = months[monthNum];
            let currYear = date.getFullYear();

            let lastmonth = new Date(date);// why isnt monday???????????
            lastmonth.setDate(lastmonth.getDate()+6); // setting sunday the same month
            lastmonth.setMonth(lastmonth.getMonth()-1); // set prev month 
            let nextmonth = new Date(date);
            nextmonth.setDate(nextmonth.getDate()+6);
            nextmonth.setMonth(nextmonth.getMonth());

            prevMonth.innerHTML = months[lastmonth.getMonth() -1];
            nextMonth.innerHTML = months[nextmonth.getMonth()];           

            currmonth.innerHTML = `${months[lastmonth.getMonth()]} ${lastmonth.getFullYear()}`;
            const daysInWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            var firstofmonth = new Date([date.getFullYear(),date.getMonth()+1,1].join("-")+" 0:00"); 
            var startdate = firstofmonth // why is that?????????????????

            var lastofmonth = new Date(firstofmonth);
            lastofmonth.setMonth(lastofmonth.getMonth()+1);
            lastofmonth.setDate(lastofmonth.getDate()); // is the same if -1
            var enddate = lastofmonth;

            // get the first monday of the month or closest monday of the prev and next month
            while(startdate.getDay() != 1) {
                startdate.setDate(startdate.getDate()-1);
            }
            while(enddate.getDay() != 0) {
                enddate.setDate(enddate.getDate()+1);
            }

            // // display prev month dates in calendar cells
            var currentdate = new Date(startdate);
            var weekdiv;
            var datecell;
        
            // remove all weeksUl divs to be replaced with updated ones
            while(cellsContainer.children.length > 2){
                cellsContainer.removeChild(cellsContainer.children[2]);
            }

           // displayMonth('prevMonth');
            appendDatecell(currentdate, enddate, cellsContainer);
            
        });
        function appendDatecell(currentdate,enddate,cellsContainer){
            while (currentdate <= enddate) {
                var i = currentdate.getDay();
                if(i == 1){         // 1 = Monday    
                    weekdiv = document.createElement("div");
                    weekdiv.classList.add("weeksUl");
                    weekdiv.setAttribute("data-monday",currentdate.toLocaleDateString());
                    // add data-monday attr
                    cellsContainer.appendChild(weekdiv);
                }
                datecell = document.createElement("span");
                datecell.classList.add("dateCell");
                datecell.innerHTML = currentdate.getDate();
                weekdiv.appendChild(datecell)
                currentdate.setDate(currentdate.getDate()+1);
            }
        }
        function displayMonthVertically(month){
            month.innerHTML = '';
            for(let char of month){
                month.innerHTML += `<i>${char}</i>`;
            }
            return month;
        }

        

        arrowDown.addEventListener('click', function(){
            const currmonth = document.getElementsByClassName('currmonth')[0]
            const cellsContainer = document.getElementsByClassName('cellsContainer')[0]
            let weeksUl = document.getElementsByClassName('weeksUl')
            let dateCell = document.getElementsByClassName('dateCell')
            let firstMonday = calendar.querySelectorAll('.weeksUl')[0]
            // console.log(firstMonday);

            let date = new Date(firstMonday.getAttribute("data-monday")); // every monday
            // firstMonday data-monday == date ???????????????/ why isnt?
            let monthNum = date.getMonth();
            let currMonth = months[monthNum];
            let currYear = date.getFullYear();
            // date + 6 weeks add 2 more data-monday with +1 and +2 weeks

            // let lastmonth = new Date(date);// why isnt monday???????????
            // lastmonth.setDate(lastmonth.getDate()+6); // setting sunday the same month
            // lastmonth.setMonth(lastmonth.getMonth()-1); // set month 
            // let nextmonth = new Date(date);
            // nextmonth.setDate(nextmonth.getDate()+6);
            // nextmonth.setMonth(nextmonth.getMonth());

            // prevMonth.innerHTML = months[lastmonth.getMonth() -1];
            // nextMonth.innerHTML = months[nextmonth.getMonth()];
            // currmonth.innerHTML = `${months[lastmonth.getMonth()]} ${lastmonth.getFullYear()}`;

            var firstofmonth = new Date([date.getFullYear(),date.getMonth()+1,1].join("-")+" 0:00"); 
            var startdate = firstofmonth // why is that?????????????????

            var lastofmonth = new Date(firstofmonth);
            lastofmonth.setMonth(lastofmonth.getMonth()+2);
            lastofmonth.setDate(lastofmonth.getDate()-7);
            var enddate = lastofmonth;

            // get the first monday of the month or closest monday of the prev and next month
            while(startdate.getDay() != 1) {
                startdate.setDate(startdate.getDate()-1);
            }
            while(enddate.getDay() != 0) {
                enddate.setDate(enddate.getDate()+1);
            }


         // display prev month dates in calendar cells
            var currentdate = new Date(startdate);
            var weekdiv;
            var datecell;

            // remove all weeksUl divs to be replaced with updated ones
            while(cellsContainer.children.length > 2){
                cellsContainer.removeChild(cellsContainer.children[2]);
            }

            // display next 2 weeks appended to curr calendar
            while (currentdate <= enddate) {
                var i = currentdate.getDay();
                if(i == 1){         // 1 = Monday    
                    weekdiv = document.createElement("div");
                    weekdiv.classList.add("weeksUl");
                    weekdiv.setAttribute("data-monday",currentdate.toLocaleDateString());
                    // add data-monday attr
                    cellsContainer.appendChild(weekdiv);
                }
                datecell = document.createElement("span");
                datecell.classList.add("dateCell");
                datecell.innerHTML = currentdate.getDate();
                weekdiv.appendChild(datecell)
                currentdate.setDate(currentdate.getDate()+1);
            }
           
        });

        </script>
        <?php
        $content .= ob_get_clean();
        return $content;
   }
   
}