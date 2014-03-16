<?php



function formatTimeStamp($timestamp=false, $formatString="F-j-Y h:i:d A") {
	// 01-23-07 - for use in currentValue and currentValueFromLists, when you want to 
	// format a timestamp.


/* 

09-10-08-

Melissa Shore wrote:
> Hey,
> 
> I know we've asked you this before and I'm sure it didn't seem like a
> priority, but it still bugs me that the time in the chat rooms is Mountain
> Time or something weird. Its an hour earlier than EST. Could you put the
> correct EST time in and actually write EST next to it?


I'm correcting this right now. But let me explain what I'm doing. 

Most of the time, when we want to record the time an entry is created, we record a "timestamp". In the lingo of computers, this is the number of seconds that have passed since the start of January 1st, 1970 (the dawn of the modern computing era). So a typical Sharing Wall post might have a timestamp that looks like this: 

1191332876

I've been using a function called "formatTimeStamp" to turn the above number into something like this:

September-2-2008 04:14:02 PM

Since the times on the site are on Central time, rather than EST, I could adjust the time by adding 3600, which is the number of seconds in one hour: 

1191332876 + 3600

This would now give us EST. 

There is a catch. If I make this change to formatTimeStamp, it will effect maybe 90% of the dates on the site, but not all of them. There were a few places, possibly the chat room, where time is set by another mechanism. We will have to adjust those places individually. It will take some time to find them all. Let us all keep our eyes open.

I'm making the change to formatTimeStamp right now. 

-- lawrence 


*/



	if (!$timestamp) return false; 

        $timestamp = $timestamp + 3600;

	$newDate = date($formatString, $timestamp); 
	return $newDate; 
}



?>