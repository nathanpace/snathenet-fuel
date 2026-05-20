var weekdaystxtshort=["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
var weekdaystxtlong=["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur"]
var monthstxt=["January", "February", "March", "April", "May", "June",
				  "July", "August", "September", "October", "November", "December"]

function showLocalTime(container, basetime, offsetMinutes, displayversion){

if (!document.getElementById || !document.getElementById(container)) return
this.container=document.getElementById(container)
this.displayversion=displayversion

var servertimestring=basetime

this.localtime=this.serverdate=new Date(servertimestring)
this.localtime.setTime(this.serverdate.getTime()+offsetMinutes*60*1000) //add user offset to server time
this.updateTime()
this.updateContainer()
}

showLocalTime.prototype.updateTime=function(){
var thisobj=this
this.localtime.setSeconds(this.localtime.getSeconds()+1)
setTimeout(function(){thisobj.updateTime()}, 999) //update time every second
}

showLocalTime.prototype.updateContainer=function(){
var thisobj=this
if (this.displayversion=="long")
{
	var hour=this.localtime.getHours()

	var minutes=this.localtime.getMinutes()
	var seconds=this.localtime.getSeconds()

	var dayofweek=weekdaystxtlong[this.localtime.getDay()]+"day"
	var month=monthstxt[this.localtime.getMonth()]
	this.container.innerHTML=formatField(hour, 1)+":"+formatField(minutes)+"."+formatField(seconds)+" on "+
									 dayofweek+", "+month+" "+this.localtime.getDate()+", "+this.localtime.getFullYear();

} else {
	var hour=this.localtime.getHours()

	var minutes=this.localtime.getMinutes()
	var seconds=this.localtime.getSeconds()

	var dayofweek=weekdaystxtshort[this.localtime.getDay()]
	this.container.innerHTML=formatField(hour, 1)+":"+formatField(minutes)+"."+formatField(seconds)+" ("+dayofweek+")"
}
setTimeout(function(){thisobj.updateContainer()}, 999) //update container every second
}

function formatField(num, isHour){
if (typeof isHour!="undefined"){ //if this is the hour field

return (num<10)? "0"+num: num;
}
return (num<=9)? "0"+num : num//if this is minute or sec field
}
