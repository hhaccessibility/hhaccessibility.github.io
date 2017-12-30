/*
utils.js contains a few functions that may be useful for a few different JavaScript files in AccessLocator.
*/

/*
DelayedNonEmptyTimer is an object to filter very frequent events down to a frequency that efficiently uses bandwidth and server-resources.  For example, you may want to call the delayedProcess method with every key stroke but limit the frequency of HTTP requests to at most 1 request per second.

@param time_limit is a time limit in miliseconds.
@param getValue is a function used to retrieve a value.  This could be for getting a value from an input element.
@param updateCallback is a function called when the time limit was reached and getValue() returns a non-empty string.
	updateCallback would typically involve sending an HTTP request.
*/
function DelayedNonEmptyTimer(time_limit, getValue, updateCallback) {
	var self = this;
	var timer = undefined;

	function delayedProcess()
	{
		if ( timer !== undefined )
		{
			clearInterval(timer);
			timer = undefined;
		}

		timer = setTimeout(function() {
			if ( getValue().trim() !== '' ) {
				updateCallback(getValue());
			}
		}, time_limit);
	}

	self.delayedProcess = delayedProcess;
}