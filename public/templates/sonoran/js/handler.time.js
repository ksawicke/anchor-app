/**
 * Sonoran Accounting Time Handler
 *
 */
var sonoranTimeHandler = new function ()
{
//    var 

    /**
     * What to run on initialize of this class.
     *
     * @returns {undefined}
     */
    this.initialize = function () {
        $(document).ready(function () {
            sonoranTimeHandler.handleConfirmDeleteTimeEntry();
        });
    }

    /**
     * Confirms if a time entry should be deleted.
     *
     * @param {type} type
     * @returns {undefined}
     */
    this.handleConfirmDeleteTimeEntry = function () {
        $('.jconfirm').on('click', function(e){
           e.preventDefault();
           confirm( function(){
               successAlert('Confirmed');
           }, function(){
               errorAlert('Denied');
           });
           return false; 
        });
    }

}

// Initialize the class
sonoranTimeHandler.initialize();