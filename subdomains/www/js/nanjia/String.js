$String = String.prototype;
$String.trim = function() {
    return this.replace(/^\s+|\s+$/g, '');
}
$String.trimR = function() {
    return this.replace(/\s+$/, '');
}
$String.trimL = function() {
	return this.replace(/^\s+/, '');
}