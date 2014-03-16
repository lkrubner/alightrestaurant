<?php



function printLink($date, $title=null, $spPricingObject=false){
  // 2013-11-24 - this function relied on a global SpPricing object.
  // Obviously that is a mistake, so I've changed it so the pricing object
  // is a funciton argument. 

  if($spPricingObject->date() == $date){
    echo '<li class="active">';
  } else {
    echo '<li>';
  }
	
  if($title==null){
    $title = $date->format('n/j/y');
  }

  echo '<a href="event_pricing.php?d='.$date->format(P_SQL_DATE).'">'.$title.'</a>';
  echo '</li>';
}
