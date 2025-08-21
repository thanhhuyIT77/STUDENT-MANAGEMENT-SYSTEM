 
**Admin Login Details**
Email : admin@mail.com
Password: Password@123

**Teacher Login Details** 
Email : teacher@mail.com
Password: pass123

UPDATE tblclassteacher
SET password = MD5('123456')
WHERE emailAddress = 'thanhhuypm77@gmail.com';