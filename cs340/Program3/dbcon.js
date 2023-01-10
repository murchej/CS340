var mysql = require('mysql');
var pool = mysql.createPool({
  connectionLimit : 10,
  host            : 'classmysql.engr.oregonstate.edu',
  user            : 'cs340_murchej',
  password        : '9670',
  database        : 'cs340_murchej'
});
module.exports.pool = pool;
