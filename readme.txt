=== Auto Prune Posts ===
Contributors: Ramon Fincken
Tags: mass, prune, delete, clean, remove, trash, attachment, attachments, coupon, schedule, post, posts, category
Requires at least: 2.3
Tested up to: 3.4.2
Stable tag: 1.6

Auto deletes (prune) posts after a certain amount of time. On a per category basis. Handy if you want to have posts with a limited timeframe such as offers, coupons etc.. Posts will auto delete on a per category basis.

== Description ==

Auto deletes (prune) posts or pages after a certain amount of time. On a per category basis. Handy if you want to have posts with a limited timeframe such as offers, coupons etc..<br>
Posts will auto delete on a per category basis.<br>
All (custom)post types are supported.<br>
Will also trash post attachments.<br>
No cronjob needed :)<br>

* Coding by <a href="http://www.mijnpress.nl" title="MijnPress.nl WordPress ontwikkelaars">MijnPress.nl</a><br>
* Idea by <a href="http://www.nostromo.nl">Nostromo.nl</a>


== Installation ==

1. Upload directory `auto_prune_posts` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit Plugins menu to configure the plugin.

== Frequently Asked Questions ==

= I have a lot of questions and I want support where can I go? =

<a href="http://pluginsupport.mijnpress.nl/">http://pluginsupport.mijnpress.nl/</a> or drop me a tweet to notify me of your support topic over here.<br>
I always check my tweets, so mention my name with @ramonfincken and your problem.


== Changelog ==
= 1.6 =
Added: All categories support
Bugfix: Only get latest 50 posts (performance fix), every 30 seconds
Changed: Dropdown now on category name (sort)


= 1.5 =
Bugfix: Framework did not work on multisite, is_admin() problem.<br>If anyone could help me with that ? :)

= 1.1 =
Second release<br/>
Added: Custom post type support<br>
Added: Trash OR force delete option<br>
Added: Mail admin if post is deleted<br>
Changed: Init method is now every 30 seconds, all posts are checked.<br>
Added: If you add &prune=true in your admin plugin page the plugin will run manually (force run)<br>

= 1.0 =
First release

== Screenshots ==
Updated screenshots of version 1.1 will follow<br><br>

1. Start overview
2. All options, you can update or delete each setting
