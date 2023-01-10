
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Company DB</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

    <style type="text/css">

        .wrapper{
            width: 500px;
            margin: 0 auto;
        }

    </style>

</head>

<body>

    <div class="wrapper">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="page-header">

                        <h3>Update Record for Dependent of Employee with SSN =  <?php echo $_SESSION["Ssn"]; ?> </H3>
                        
                    </div>

                    <p>Please edit the input values and submit to update.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

						<div class="form-group <?php echo (!empty($Dname_err)) ? 'has-error' : ''; ?>">
                            <label>Dependent Name</label>
                            <input type="text" name="Dname" class="form-control" value="<?php echo $Dname; ?>">
                            <span class="help-block"><?php echo $Dname_err;?></span>
                        </div>

						<div class="form-group <?php echo (!empty($Relationship_err)) ? 'has-error' : ''; ?>">
                            <label>Relationship</label>
                            <input type="text" name="Relationship" class="form-control" value="<?php echo $Relationship; ?>">
                            <span class="help-block"><?php echo $Relationship_err;?></span>
                        </div>

						<div class="form-group <?php echo (!empty($Sex_err)) ? 'has-error' : ''; ?>">
                            <label>Sex</label>
                            <input type="text" name="Sex" class="form-control" value="<?php echo $Sex; ?>">
                            <span class="help-block"><?php echo $Sex_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($Birth_err)) ? 'has-error' : ''; ?>">
                            <label>Birth date</label>
                            <input type="date" name="Bdate" class="form-control" value="<?php echo $Bdate; ?>">
                            <span class="help-block"><?php echo $Birth_err;?></span>
                        </div>
   						
                        <input type="hidden" name="Ssn" value="<?php echo $Essn; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>

            </div> 

        </div>
    </div>

</body>

</html>



<?php

	session_start();	

	require_once "config.php";
 

$Dname = $Bdate = $Relationship = $Sex = "";

$Dname_err = $Sex_err = $Relationship_err = $Bdate_err = "" ;


if(isset($_GET["Dname"]) && !empty(trim($_GET["Dname"]))){

	$_SESSION["Dname"] = $_GET["Dname"];

    
    $sql1 = "SELECT * FROM DEPENDENT WHERE Essn = ? AND Dependent_name = ?";
  
    if($stmt1 = mysqli_prepare($link, $sql1)){
        
        mysqli_stmt_bind_param($stmt1, "is", $param_Essn, $param_Dname);      
        
       $param_Essn = $_SESSION["Ssn"];

       $param_Dname = $_SESSION["Dname"];

        
        if(mysqli_stmt_execute($stmt1)){

            $result1 = mysqli_stmt_get_result($stmt1);

			if(mysqli_num_rows($result1) > 0){

				$row = mysqli_fetch_array($result1);

				$Dname = $row['Dependent_name'];
                
				$Essn = $row['Essn'];
                
				$Bdate = $row['Bdate'];
                
				$Relationship = $row['Relationship'];
                
				$Sex = $row['Sex'];
			}
		}
	}
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $Essn = $_SESSION["Ssn"];
    
    $oldDname = $_SESSION["Dname"];
   
    $Dname = trim($_POST["Dname"]);

    if(empty($Dname)){

        $Dname_err = "Please enter a name.";

    } 
    elseif(!filter_var($Dname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){

        $Dname_err = "Please enter a valid name.";

    } 

    $Relationship = trim($_POST["Relationship"]);

    if(empty($Relationship)){

        $Relationship_err = "Please enter a relationship.";

    } 
    elseif(!filter_var($Relationship, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){

        $Relationship_err = "Please enter a relationship.";

    }  
  
    $Sex = trim($_POST["Sex"]);

    if(empty($Sex)){

        $Sex_err = "Please enter Sex.";  

    }
     elseif(!filter_var($Sex, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){

        $Sex_err = "Please enter letters only.";

    } 
  
    $Bdate = trim($_POST["Bdate"]);

    if(empty($Bdate)){

        $Bdate_err = "Please enter birthdate.";     

    }

 
    if(empty($Dname_err) && empty($Relationship_err) && empty($Bdate_err) && empty($Sex_err)){
      
        $sql = "UPDATE DEPENDENT SET Dependent_name=?, Relationship=?, Sex=?, Bdate =? WHERE Essn=? AND Dependent_name =?";
    
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "ssssis", $param_Dname, $param_Relationship, $param_Sex,$param_Bdate,$param_Essn, $param_oldDname);
            
            
            $param_Dname = $Dname;

			$param_Relationship = $Relationship;    

			$param_Sex = $Sex;

            $param_Bdate = $Bdate;

            $param_Essn = $Essn;

            $param_oldDname = $oldDname;
            
            
            if(mysqli_stmt_execute($stmt)){
              
                header("location: index.php");

                exit();

            } 
            else{
                
                echo "<center><h2>Error when updating</center></h2>";

                $SQL_err = mysqli_error($link);

                $Dname_err = $SQL_err;

            }
        }        
  
        mysqli_stmt_close($stmt);

    }
    
    mysqli_close($link);

}

?>

