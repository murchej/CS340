function searchEmployeesByFirstName() {
    
    var first_name_search_string  = document.getElementById('first_name_search_string').value
    
    window.location = '/employee/search/' + encodeURI(first_name_search_string)
    
}