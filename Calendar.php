<?php
class Calendar{
   // PROPS
   private $daysInWeek = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');

   public function show()
   {    
        $content = '<section id="calendar">';      
        $count = 1;
        $content .= '<div class="mainContainer">';
        $content .= '<div class="months"></div>';
        // $content .= '<p class="nextMonth">' . $nextMonth . '</p></div>'; // months
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
            if(($count % 7) === 1) $content .= '<div class="weeksUl" data-monday="'.date('Y M d',$timestamp).'">';
            if($timestamp === strtotime("Today")){
                $content .=  '<span class="dateCell today">' . date('d',$timestamp) . '</span>';
            }else{
                $content .=  '<span class="dateCell">' . date('d',$timestamp) . '</span>';
            }
            if(($count % 7) === 0) $content .= '</div>'; // weeksUL
            $count++;
        };
        $content .= '</div>'; ///weeksUlwrapper
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
        const months = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        let calendar = document.getElementById('calendar')  
        const month = document.getElementsByClassName('months')[0]
        const cellsContainer = document.getElementsByClassName('cellsContainer')[0]
        let weeksUl = document.getElementsByClassName('weeksUl')
        let dateCell = document.getElementsByClassName('dateCell')
            
        
    
        arrowDown.addEventListener('click', function(){
            const currmonth = document.getElementsByClassName('currmonth')[0]
            let dateCell = document.getElementsByClassName('dateCell')
            let firstMondayDiv = calendar.querySelectorAll('.weeksUl')[0]
            let lastMonday = calendar.querySelector('.weeksUlwrapper').lastChild

            let firstMonDate = new Date(firstMondayDiv.getAttribute("data-monday")); // every monday
            let monthFirstM = months[firstMonDate.getMonth()];
            let dayFirstM = firstMonDate.getDate();

            let lastMon = new Date(lastMonday.getAttribute("data-monday")); 
            let monthLastM = months[lastMon.getMonth()];
            let dayLastM = lastMon.getDate();   
  
            let startdate = new Date(lastMon);
            startdate.setDate(startdate.getDate()+7);
            let enddate = new Date(startdate);
            enddate.setDate(enddate.getDate()+13);
            let currentdate = new Date(startdate)

            appendDatecell(currentdate, enddate, weeksUlwrapper)
            displayMonths()
        });

        arrowUp.addEventListener('click', function(){
            const currmonth = document.getElementsByClassName('currmonth')[0]
            let dateCell = document.getElementsByClassName('dateCell')
            let firstMondayDiv = calendar.querySelectorAll('.weeksUl')[0]
            let lastMonday = calendar.querySelector('.weeksUlwrapper').lastChild

            let enddate = new Date(firstMondayDiv.getAttribute("data-monday"))
            enddate.setDate(enddate.getDate()-1)
            //console.log(startdate)
            let startdate = new Date(enddate)
            startdate.setDate(startdate.getDate()-13)
            console.log(startdate + ' - ' + enddate)
            let currentdate = new Date(startdate)

            let docFrag = document.createDocumentFragment()

            appendDatecell(currentdate, enddate, docFrag)
            weeksUlwrapper.prepend(docFrag)
            displayMonths()
        });
        displayMonths()

        function appendDatecell(currentdate,enddate,cellsContainer){
            while (currentdate <= enddate) {
                var i = currentdate.getDay();
                if(i == 1){         // 1 = Monday    
                    weekdiv = document.createElement("div");
                    weekdiv.classList.add("weeksUl");
                        weekdiv.setAttribute("data-monday",currentdate.toLocaleDateString());
                        weekdiv.classList.add("nextweeks");
                        // add data-monday attr
                        cellsContainer.appendChild(weekdiv);
                }
                datecell = document.createElement("span");
                datecell.classList.add("dateCell");
                datecell.innerHTML = currentdate.getDate();
                datecell.innerHTML += " "+months[currentdate.getMonth()];
                weekdiv.appendChild(datecell)
                currentdate.setDate(currentdate.getDate()+1);
            }
        }     

        function displayMonths(){
            let startDate = new Date(weeksUlwrapper.children[0].getAttribute('data-monday'))
            startDate.setDate(1);
            let endDate =  new Date(weeksUlwrapper.children[weeksUlwrapper.children.length-1].getAttribute('data-monday'))
            endDate.setDate(endDate.getDate()+6)     
            
            let currentdate = new Date(startDate);

            // delete prev months
            while(month.children.length){
                month.removeChild(month.children[0])
            }
        
            while (currentdate.getTime() <= endDate.getTime()){
                //add button for this month
                let btn = document.createElement('button');
                btn.setAttribute('data-month', currentdate.getTime())
                btn.innerHTML = months[currentdate.getMonth()];
                btn.addEventListener('click', function(){
                    const currmonth = document.getElementsByClassName('currmonth')[0]
                    let firstMonday = calendar.querySelectorAll('.weeksUl')[0]

                    let date = new Date(firstMonday.getAttribute("data-monday")); // every monday
                    let monthNum = date.getMonth();
                    let currMonth = months[monthNum];
                    let currYear = date.getFullYear();

                    let lastmonth = new Date(date);
                    lastmonth.setDate(lastmonth.getDate()+6);
                    lastmonth.setMonth(lastmonth.getMonth()-1); // set prev month 
                    let nextmonth = new Date(date);
                    nextmonth.setDate(nextmonth.getDate()+6);
                    nextmonth.setMonth(nextmonth.getMonth());         

                    currmonth.innerHTML = `${months[lastmonth.getMonth()]} ${lastmonth.getFullYear()}`;
                    const daysInWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

                    var firstofmonth = new Date([date.getFullYear(),date.getMonth()+1,1].join("-")+" 0:00"); 
                    firstofmonth.setMonth(firstofmonth.getMonth()-1)
                    var startdate = firstofmonth // why is that?????????????????

                    var lastofmonth = new Date(firstofmonth);
                    lastofmonth.setMonth(lastofmonth.getMonth()+1);
                    lastofmonth.setDate(lastofmonth.getDate()-1);
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

                    // remove all weeksUl divs to be replaced with updated ones
                    while(cellsContainer.children.length > 2){
                        cellsContainer.removeChild(cellsContainer.children[2]);
                    }

                    // displayMonth('prevMonth');
                    let weekdiv;
                    let datecell;
                    appendDatecell(currentdate, enddate, cellsContainer);
                });
                month.appendChild(btn)
                currentdate.setMonth(currentdate.getMonth()+1);
            }
        }
        </script>
        <?php
        $content .= ob_get_clean();
        return $content;
   }
   
}