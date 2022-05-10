<?php 
/*
 ** Users 
  1 - admin   [name , email , password , phone , image ]
  2 - writer  [name , email , password , phone , image ]

  ** blog  
  1 - Category   [title]
  2 - content of blog  [title , content , image , addedBy , date ]



============================================= 
roles 
id    title  


users 
id name  email  password  phone  image   role_id 



category 
id    title 

Blog 
id title  content  image  addedBy  date    cat_id    




 cat      blog 
 1        m 
 1        1 
============
1         m 





users      blog 
1           m 
1           1
==============
1           m 

*/








?>