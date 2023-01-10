function filterEmployeesbyProject() {
    
    var project_id = document.getElementById('project_filter').value

    window.location = '/employee/filter/' + parseInt(project_id)

}
