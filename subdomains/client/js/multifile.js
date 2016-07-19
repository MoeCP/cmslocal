/**
 * Convert a single file-input element into a 'multiple' input list
 *
 * Usage:
 *
 *   1. Create a file input element (no name)
 *      eg. <input type="file" id="first_file_element">
 *
 *   2. Create a DIV for the output to be written to
 *      eg. <div id="files_list"></div>
 *
 *   3. Instantiate a MultiSelector object, passing in the DIV and an (optional) maximum number of files
 *      eg. var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 3 );
 *
 *   4. Add the first element
 *      eg. multi_selector.addElement( document.getElementById( 'first_file_element' ) );
 *
 *   5. That's it.
 *
 *   You might (will) want to play around with the addListRow() method to make the output prettier.
 *
 *   You might also want to change the line 
 *       element.name = 'file_' + this.count;
 *   ...to a naming convention that makes more sense to you.
 * 
 * Licence:
 *   Use this however/wherever you like, just don't blame me if it breaks anything.
 *
 * Credit:
 *   If you're nice, you'll leave this bit:
 *  
 *   Class by Stickman -- http://www.the-stickman.com
 *      with thanks to:
 *      [for Safari fixes]
 *         Luis Torrefranca -- http://www.law.pitt.edu
 *         and
 *         Shawn Parker & John Pennypacker -- http://www.fuzzycoconut.com
 *      [for duplicate name bug]
 *         'neal'
 */
function MultiSelector( list_target, form, formdiv, processdiv, removeform, max ){

	// Where to write the list
	this.list_target = list_target;
	// How many elements?
	this.count = 0;
    this.form = form;
    this.formdiv = formdiv;
    this.processdiv = processdiv;
    this.removeform = removeform;
    this.current_element = null;
	// How many elements?
	this.id = 0;
	// Is there a maximum?
	if( max ){
		this.max = max;
	} else {
		this.max = -1;
	};
	
	/**
	 * Add a new file input element
	 */
	this.addElement = function( element ){

		// Make sure it's a file input element
		if( element.tagName == 'INPUT' && element.type == 'file' ){
            
			// Element name -- what number am I?
			//element.name = 'file_' + this.id++;

			// Add reference to this object
			element.multi_selector = this;
            

			// What to do when a file is selected
			element.onchange = function(){
                this.multi_selector.processdiv.style.visibility = 'visible';
                this.multi_selector.formdiv.style.visibility = 'hidden';
                this.multi_selector.form.submit();
                this.value = null;
			};
			// If we've reached maximum number, disable input element
			if( this.max != -1 && this.count >= this.max ){
				element.disabled = true;
			};

			// File element counter
			this.count++;
			// Most recent element
			this.current_element = element;
			
		} else {
			// This can only be applied to file input elements!
			alert( 'Error: not a file input element' );
		};

	};

    this.stopUpload = function(success, filename, prefix) {
        this.current_element.value = null;
        this.processdiv.style.visibility = 'hidden';
        this.formdiv.style.visibility = 'visible';       
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
         this.addListRow( this.current_element, filename, prefix);
         return true;
      } else if (success == 0) {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
         result = 'There was an error during file upload!';
      } else if (success == -1) {
          result = 'UPLOAD FAILURE: invaild file type, please to check you file type';
      } else if (success == -2) {
          result = 'UPLOAD FAILURE: file is too large';
      } else if (success == -3) {
          result = 'UPLOAD FAILURE: The uploaded file was only partially uploaded';
      } else if (success == -4) {
          result = 'UPLOAD FAILURE: No file was uploaded';
      } else if (success == -6) {
          result = 'UPLOAD FAILURE: Missing a temporary folder';
      } else if (success == -7 || success == -8) {
          result = 'UPLOAD FAILURE: please contact the system admin';
      }
      alert(result);
      return true;   
    }

	/**
	 * Add a new row to the list of files
	 */
	this.addListRow = function( element, filename, prefix ){

		// Row div
		var new_row = document.createElement( 'div' );
        
		// checkbox button
		var new_row_checkbox = document.createElement( 'input' );
		new_row_checkbox.type = 'checkbox';
        new_row_checkbox.name = 'attachments[]';
        new_row_checkbox.setAttribute('checked', 'checked');
		new_row_checkbox.defaultChecked = true;
		new_row_checkbox.value = prefix + filename;

		// References
		new_row.element = element;
		// Delete function
        this.count++;
        if (this.max <= this.count) {
            this.current_element.disabled = true;
        } else {
            this.current_element.disabled = false;
        }
        //this.current_element.value = '';
		new_row_checkbox.onclick = function(){
            if (!this.checked)
            {
                // Disable element from form
                this.disabled = true;
                // this.parentNode.element.disabled = true;
                // Remove this row from the list
                //this.parentNode.parentNode.removeChild( this.parentNode );

                // Decrement counter
                this.parentNode.element.multi_selector.count--;
                // Re-enable input element (if it's disabled)
                this.parentNode.element.multi_selector.current_element.disabled = false;
                this.parentNode.element.multi_selector.removeform.filename.value = this.value;
                this.parentNode.element.multi_selector.removeform.submit();
                this.parentNode.remove();
                // this.parentNode; = null;
                //this.multi_selector.current_element.value = '';
            }
            return true;
		};

		// Set row value
		// new_row.innerHTML = element.value;
		// Add button
		new_row.appendChild( new_row_checkbox );

		var new_row_hidden = document.createElement( 'input' );
		new_row_hidden.type = 'hidden';
        new_row_hidden.name = 'filenames[]';
		new_row_hidden.value = filename;
        new_row.appendChild( new_row_hidden );

        var new_row_label = document.createElement( 'label' );
        new_row_label.innerHTML = filename;
        new_row.appendChild(new_row_label);
		// Add it to the list
		this.list_target.appendChild( new_row );
	};
};