$Array = Array.prototype;
$Array.append=function(obj, nodup) {
	if (!(nodup && this.contains(obj))){
	    this[this.length] = obj;
	}
}

$Array.indexOf=function(obj){
	var result=-1;
	for (var i=0; i<this.length; i++){
		if (this[i]==obj){
			result=i;
			break;
		}
	}
	return result;
}

$Array.contains=function(obj) {
	return (this.indexOf(obj)>=0);
}

$Array.clear=function(){
	this.length=0;
}

$Array.insertAt=function(index, obj){
	this.splice(index, 0, obj);
}

$Array.removeAt=function(index){
    this.splice(index,1);
}

$Array.remove=function(obj){
	var index=this.indexOf(obj);
	if (index>=0){
	  this.removeAt(index);
	}
}