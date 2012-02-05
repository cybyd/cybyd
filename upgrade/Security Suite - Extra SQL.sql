
INSERT INTO `xbtit_settings` (`key`, `value`) VALUES
('secsui_quarantine_dir', ''), 
('secsui_quarantine_search_terms', '<?php,base64_decode,base64_encode,eval(,phpinfo,fopen,fread,fwrite,file_get_contents'), 
('secsui_cookie_name', ''), 
('secsui_quarantine_pm', '2'), 
('secsui_pass_type', '1'), 
('secsui_ss', ''), 
('secsui_cookie_type', '1'), 
('secsui_cookie_exp1', '1'), 
('secsui_cookie_exp2', '3'), 
('secsui_cookie_path', ''), 
('secsui_cookie_domain', ''), 
('secsui_cookie_items', '1-0,2-0,3-0,4-0,5-0,6-0,7-0,8-0[+]0'),
('secsui_pass_min_req', '4,0,0,0,0');

ALTER TABLE `xbtit_users`
ADD `salt` VARCHAR(20) NOT NULL DEFAULT '' AFTER `password`,
ADD `pass_type` ENUM('1','2','3','4','5','6','7') NOT NULL DEFAULT '1' AFTER `salt`,
ADD `dupe_hash` VARCHAR(20) NOT NULL DEFAULT '' AFTER `pass_type`;
