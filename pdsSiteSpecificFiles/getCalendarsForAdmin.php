<?php



function getCalendarsForAdmin() {
  // 2013-11-13 - right now this is in use here:
  //
  // http://dev4.krubner.com/admin.php?page=admin_calendar
  //
  // this brings back 2 months worth of days to show in a calendar

  global $controller; 

  $today = new DateTime(date('Y-m-d'));

  //Get Calendar for this week
  if(!isset($_GET['ym'])){
    $top_month = date('Y-m');
  } else {
    $top_month = $_GET['ym'];
  }

  $firstDayOfMonthDateTime = new DateTime($top_month."-01");
  $lastDayOfMonthDateTime = clone $firstDayOfMonthDateTime;
  $lastDayOfMonthDateTime->modify("+1 month");
  $lastDayOfMonthDateTime->modify("-1 day");

  $arrayOfDaysForThisMonth = array();
  $arrayOfDaysForThisMonth = $controller->command("loadAllNights", $firstDayOfMonthDateTime, $lastDayOfMonthDateTime); 

  $calendars = array();
  $calendars[$top_month] = $arrayOfDaysForThisMonth;

  $firstDayOfMonthDateTime2 = clone $firstDayOfMonthDateTime;
  $firstDayOfMonthDateTime2->modify("+1 month");
  $lastDayOfMonthDateTime2 = clone $firstDayOfMonthDateTime2;
  $lastDayOfMonthDateTime2->modify("+1 month");
  $lastDayOfMonthDateTime2->modify("-1 day");

  $arrayOfDaysForThisMonth = array();
  $arrayOfDaysForThisMonth = $controller->command("loadAllNights", $firstDayOfMonthDateTime2, $lastDayOfMonthDateTime2); 

  $calendars[$lastDayOfMonthDateTime2->format('Y-m')] = $arrayOfDaysForThisMonth;

  return $calendars; 
}