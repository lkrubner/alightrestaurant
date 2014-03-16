alight cms
==========

In 2002 I created some weblog software, written in PHP/MySql, and from 2003 to 2006 myself and some friends ran a company that tried to promote the software. We could not gain traction against Blogger and TypePad, especially after Blogger was acquired by Google. After 2006, I extracted a simple framework from the software, and used that framework for several clients, until 2008. The organizing principle was simple: a single object, the Controller, which handled the import of all needed functionality. After 2008, I started using Symfony for all PHP work. I had not used my own framework for 6 years, but then I got a request to rebuild a simple site, where Symfony seemed like it would be overkill, and so I once again used the old framework. I post it here as an example of the work I did circa 2002 - 2008. I have in recent years become critical of PHP, you can read my criticisms here: 

http://www.smashcompany.com/technology/why-php-is-obsolete


What was working in 2006: 

1.) a scaffolding system that auto-generated HTML forms for interacting with the database. 

2.) a GUI that allowed people without much tech skill to create new database tables, sort of like phpMyAdmin, but with the aim of becoming more like FormStack or WuFoo. 

I have scrubbed this repo of most of the client-specific code, so it would be difficult to take this and use it as a framework, nor does the world need another framework. I post it mostly to show what I was working on before I switched to using Symfony. 


