--URL for my webpage: https://web.engr.oregonstate.edu/~murchej/cs340/


CREATE DEFINER=`cs340_murchej`@`%` PROCEDURE `InitDeptStats`()
BEGIN
INSERT INTO DEPT_STATS
	SELECT 
    Dnumber, 
    COUNT(*),
    AVG(Salary)
    FROM DEPARTMENT, EMPLOYEE
    WHERE DEPARTMENT.Dnumber = EMPLOYEE.Dno
    GROUP BY Dnumber;
END


CREATE TRIGGER `DELETEDeptStats` 
AFTER DELETE ON EMPLOYEE 
FOR EACH ROW
BEGIN
	DELETE FROM DEPT_STATS;
    CALL InitDeptStats();
END 


CREATE TRIGGER `INSERTDeptStats` AFTER INSERT ON `EMPLOYEE`
    BEGIN
	IF NEW.Dno IS NOT NULL THEN
    	UPDATE DEPT_STATS
		SET Emp_count = Emp_count + 1
        	WHERE DEPT_STATS.Dnumber = NEW.Dno;
    END IF;
    
    IF NEW.Dno IS NOT NULL THEN
    	UPDATE DEPT_STATS 
        SET Avg_salary = (SELECT AVG(Salary)
                             FROM EMPLOYEE
                             WHERE EMPLOYEE.Dno = NEW.Dno)
        WHERE DEPT_STATS.Dnumber = NEW.Dno;
   END IF;
END


CREATE TRIGGER `UPDATEDeptStats` AFTER UPDATE ON `EMPLOYEE`
  BEGIN
	IF (OLD.Dno <> NEW.Dno) THEN
    	UPDATE DEPT_STATS
        SET Emp_count = Emp_count + 1
        WHERE Dnumber = NEW.Dno;
        
        UPDATE DEPT_STATS 
        SET Avg_salary = (SELECT AVG(Salary)
                          FROM EMPLOYEE
                          WHERE EMPLOYEE.Dno = NEW.Dno)
        WHERE DEPT_STATS.Dnumber = NEW.Dno;
                         
    	UPDATE DEPT_STATS
        SET Emp_count = Emp_count - 1
        WHERE Dnumber = OLD.Dno;
        
    	UPDATE DEPT_STATS 
        SET Avg_salary = (SELECT AVG(Salary)
                          FROM EMPLOYEE
                          WHERE EMPLOYEE.Ssn != OLD.Ssn 
                          and EMPLOYEE.Dno = OLD.Dno)
        WHERE DEPT_STATS.Dnumber = OLD.Dno;
    END IF;
END


CREATE TRIGGER `MaxTotalHours` BEFORE INSERT ON `WORKS_ON`
BEGIN
	DECLARE errMsg VARCHAR(100);
    DECLARE totalHours int(2);
    
    SELECT SUM(Hours) INTO totalHours
    	FROM WORKS_ON
    	WHERE WORKS_ON.Essn = NEW.Essn;
    
    IF NEW.Hours > 40 THEN
        SET errMsg = concat('You entered ', NEW.Hours, '. You currently work ', totalHours, '. You are over 40 hours.');
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = errMsg;
    END IF;
    
    IF totalHours + NEW.Hours > 40 THEN
        SET errMsg = concat('You entered ', NEW.Hours, '. You currently work ', totalHours, '. You are over 40 hours.');
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = errMsg;
    END IF;
END


CREATE DEFINER=`cs340_murchej`@`%` FUNCTION `PayLevel`(`empSsn` INT) RETURNS varchar(100)
	BEGIN
	DECLARE empSal decimal(10,2);
    DECLARE deptSal decimal(10,2);
    
    SELECT EMPLOYEE.Salary into empSal
    FROM EMPLOYEE
    WHERE EMPLOYEE.Ssn = ssn;
    
    SELECT DEPT_STATS.Avg_salary into deptSal
    FROM DEPT_STATS, EMPLOYEE
    WHERE DEPT_STATS.Dnumber = EMPLOYEE.Dno
    AND EMPLOYEE.Ssn = ssn;
    
    IF empSal > deptSal THEN
    	RETURN 'Above Average';
    END IF;

    IF empSal < deptSal THEN
    	RETURN 'Below Average';
	END IF;
    
    IF empSal = deptSal THEN
    	RETURN 'Average';
   	END IF;
END
