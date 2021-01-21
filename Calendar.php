<?php
class Calendar{
   // PROPS
   private $daysInWeek = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun','Week');
   //private $weekNumber;

   public function show()
   {    
        $content = '<section id="calendar">';      
        $count = 1;
        $content .= '<div class="mainContainer">';
        $content .= '<div class="months"></div>';
        $content .= '<div class="cellsContainer">';
        $content .= '<i class="arrow up"></i>';  
        $content .= '<span class="currmonth">'. date('M Y') . '</span>';
        $content .= '<ul class="days">';
        for($i=0; $i<count($this->daysInWeek); $i++){
           $content .= '<li>' .$this->daysInWeek[$i] . '</li>';
        }
        $content .= '</ul>';
        $content .= '<div class="weeksUlwrapper">';

        for ($timestamp = strtotime("-2 weeks Mon"); $timestamp <= strtotime("+2 weeks Sun"); $timestamp = strtotime("+1 day",$timestamp)) {
            if(($count % 7) === 1) $content .= '<div class="weeksUl" data-monday="'. ($timestamp * 1000) . '">';
    
            $content .=  '<span class="dateCell'.($timestamp === strtotime("Today") ? " today" : "").(date('m',$timestamp) % 2 == 0 ? " oddMonth" : " evenMonth") . '" data-date=" ' .date('Y M d', $timestamp ) .'" data-month="'.date("Yn",$timestamp).'">' . date('d',$timestamp) . '</span>';
              
            if(($count % 7) === 0) $content .= '<span class="dateCell week">' . date('W', $timestamp) . '</span></div>'; // weeksUL
            $count++;
        };
        $content .= '</div>'; ///weeksUlwrapper
        $content .= '<ul class="days">';
        for($i=0; $i<count($this->daysInWeek); $i++){
           $content .= '<li>' .$this->daysInWeek[$i] . '</li>';
        }
        $content .= '</ul>'; 
        $content .= '<i class="arrow down"></i><br>';
        $content .= '<button" class="submitBtn">Submit </button></section>';
        $content .= '</div></div>'; // currMonthContainer/cellsContainer  
        ob_start();
        ?>

        <script>
        // // get all php els to sabstitude content in js
        const section = document.querySelector('#calendar')
        const arrowUp = document.getElementsByClassName('up')[0]
        const arrowDown = document.getElementsByClassName('down')[0]
        const mainContainer = document.getElementsByClassName('mainContainer')[0]
        var weeksUlwrapper = document.getElementsByClassName('weeksUlwrapper')[0]
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        let calendar = document.getElementById('calendar')  
        const month = document.getElementsByClassName('months')[0]
        const cellsContainer = document.getElementsByClassName('cellsContainer')[0]
        let weeksUl = document.getElementsByClassName('weeksUl')
        let dateCell = document.getElementsByClassName('dateCell')
        let currmonth = document.getElementsByClassName('currmonth')[0]
            
        
    
        arrowDown.addEventListener('click', function(){
           // let dateCell = document.getElementsByClassName('dateCell')
            let firstMondayDiv = calendar.querySelectorAll('.weeksUl')[0]
            let lastMonday = calendar.querySelector('.weeksUlwrapper').lastChild

            let firstMonDate = new Date(Number(firstMondayDiv.getAttribute("data-monday"))); // every monday
            let monthFirstM = months[firstMonDate.getMonth()];
            let dayFirstM = firstMonDate.getDate();

            let lastMon = new Date(Number(lastMonday.getAttribute("data-monday"))); 
            let monthLastM = months[lastMon.getMonth()];
            let dayLastM = lastMon.getDate();   
  
            let startdate = new Date(lastMon);
            startdate.setDate(startdate.getDate()+7);
            let enddate = new Date(startdate);
            enddate.setDate(enddate.getDate()+13);
            let currentdate = new Date(startdate)

            appendDatecell(currentdate, enddate, weeksUlwrapper)
            currmonth.innerHTML = ''
            displayMonths()
        });

        arrowUp.addEventListener('click', function(){
            // let dateCell = document.getElementsByClassName('dateCell')
            let firstMondayDiv = calendar.querySelectorAll('.weeksUl')[0]
            let lastMonday = calendar.querySelector('.weeksUlwrapper').lastChild

            let enddate = new Date(Number(firstMondayDiv.getAttribute("data-monday")))
            enddate.setDate(enddate.getDate()-1)
            //console.log(startdate)
            let startdate = new Date(enddate)
            startdate.setDate(startdate.getDate()-13)
            let currentdate = new Date(startdate)

            let docFrag = document.createDocumentFragment()

            appendDatecell(currentdate, enddate, docFrag)
            weeksUlwrapper.prepend(docFrag)
            currmonth.innerHTML = ''
            displayMonths()
        });
         displayMonths()

         window.addEventListener('resize', displayMonths);

        function appendDatecell(currentdate,enddate,cellsContainer){
            var weekdiv;
            while (currentdate <= enddate) {
                var i = currentdate.getDay();
                if(i == 1){         // 1 = Monday    
                    weekdiv = document.createElement("div");
                    weekdiv.classList.add("weeksUl");
                    weekdiv.setAttribute("data-monday",currentdate.getTime());
                    cellsContainer.appendChild(weekdiv);
                 }

                let datecell = document.createElement("span");
                datecell.classList.add("dateCell");
                datecell.setAttribute('data-date',currentdate.getTime())
                datecell.setAttribute('data-month',""+currentdate.getFullYear()+(currentdate.getMonth()+1));
                let d = currentdate.getDate();
                datecell.innerHTML = d <= 9 ? `0${d}` : d;

                // to color different months, add class oddMonth || evenMonth
                let coloredMonthIndex = currentdate.getMonth()
                if(coloredMonthIndex % 2 == 0){
                    datecell.classList.add('evenMonth')
                }else{
                    datecell.classList.add('oddMonth');
                }
                weekdiv.appendChild(datecell)

                if(i==0){
                    let result = getWeekNumber(currentdate); // arr[1] is the week num
                    var weekNum = document.createElement('span');
                    weekNum.classList.add('dateCell');
                    weekNum.classList.add('week');
                    weekNum.innerHTML = result[1] <= 9 ? `0${result[1]}` : result[1];
                    weekdiv.appendChild(weekNum);
                }

                currentdate.setDate(currentdate.getDate()+1);
            }
        }     
        function displayMonths(){// because attribute returns str
            let startDate = new Date(Number(weeksUlwrapper.children[0].getAttribute('data-monday')))
            startDate.setDate(1); // why??? do we set date for the first
            let endDate =  new Date(Number(weeksUlwrapper.children[weeksUlwrapper.children.length-1].getAttribute('data-monday')))
            endDate.setDate(endDate.getDate()+6)// why????????
            
            let currentdate = new Date(startDate);

            // delete prev months
            while(month.children.length){
                month.removeChild(month.children[0])
            }
            let months_container_pos = month.getBoundingClientRect();
            let indexoflastcell = 6;
            let btn;
            let firstcell;
            while (currentdate.getTime() <= endDate.getTime()){
                //add button for this month
                btn = document.createElement('button');
                btn.setAttribute('data-month', currentdate.getTime())
                btn.innerHTML = months[currentdate.getMonth()];
                let year = currentdate.getFullYear()

                btn.addEventListener('click', function(){
                    let startdate = new Date(Number(this.getAttribute("data-month")));
                    const currmonth = document.getElementsByClassName('currmonth')[0];
                    
                    var lastofmonth = new Date(startdate);
                    lastofmonth.setMonth(lastofmonth.getMonth()+1);
                    lastofmonth.setDate(lastofmonth.getDate()-1);
                    var enddate = lastofmonth;// last day of the curr month

                    currmonth.innerHTML = btn.innerHTML + ' ' + year
                    // get the first monday of the month or closest monday of the prev and next month
                    while(startdate.getDay() != 1) {
                        startdate.setDate(startdate.getDate()-1);
                    }
                    while(enddate.getDay() != 0) {
                        enddate.setDate(enddate.getDate()+1);
                    }

                    weeksUlwrapper.innerHTML = "";
                    appendDatecell(startdate, enddate, weeksUlwrapper);
                    // displayMonths();
                });
                month.appendChild(btn)

                //calculate where the button should be
                //find the first datecell of that month
                let cellsofmonth = weeksUlwrapper.querySelectorAll(".dateCell[data-month=\""+currentdate.getFullYear()+(currentdate.getMonth()+1)+"\"]");
                firstcell = cellsofmonth[0]; // get the monday
                let lastcell = cellsofmonth[cellsofmonth.length-1];
                let factor = (((indexoflastcell+1)%7)/7);
                let firstpixel = firstcell.getBoundingClientRect().y + firstcell.getBoundingClientRect().height * factor;
                if (month.children.length == 1) {
                    firstpixel -= firstcell.getBoundingClientRect().height;
                }

                indexoflastcell = Array.from(lastcell.parentNode.children).indexOf(lastcell);

                let lastpixel = lastcell.getBoundingClientRect().y+lastcell.getBoundingClientRect().height * (indexoflastcell+1)/7;
                btn.style.top = (firstpixel - months_container_pos.y) + "px";
                btn.style.height = (lastpixel - firstpixel)+"px";

                currentdate.setMonth(currentdate.getMonth()+1);
            }
            btn.style.height = btn.getBoundingClientRect().height + firstcell.getBoundingClientRect().height + "px";
        }
        function getWeekNumber(d) {
            // Copy date so don't modify original
            d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
            // Set to nearest Thursday: current date + 4 - current day number
            // Make Sunday's day number 7
            d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
            // Get first day of year
            var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            // Calculate full weeks to nearest Thursday
            var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
            // Return array of year and week number
            return [d.getUTCFullYear(), weekNo];
        }
        // var result = getWeekNumber(new Date()); // arr[1] is the week num
        // document.write('It\'s currently week ' + result[1] + ' of ' + result[0]);
        </script>
        <?php
        $content .= ob_get_clean();
        return $content;
   }
   
}