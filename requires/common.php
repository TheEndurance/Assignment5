    <?php
    /*
    * Function to add singles quotes to beginning and end of a string
    * Returns the string with single quotes added.
    */
    function AddQuotesToString($theString)
    {
        if (strlen($theString)>0){
        return "'" . $theString . "'";
        }
        return null;
    }
