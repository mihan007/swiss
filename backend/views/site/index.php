<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Position: Backend Developer</h1>
<h2>Task</h2>
<p>The purpose of this task is to create a method of examining a social network. You are given data (data.php)
    representing a group of people, in the form of a social graph. Each person listed has one or more connections to the
    group.</p>
<p>Come up with a database structure to store the information found in data.php,.</p>
<p>You should then create a webpage which provides functionality to choose a person within the group
    stored in the database and display the following information about this person:</p>
<ul>
    <li><i>Direct friends</i>: those people who are directly connected to the chosen user
    <li><i>Friends of friends</i>: those who are two steps away from the chosen user but not directly connected
        to the chosen user</li>
    <li><i>Suggested friends</i>: people in the group who know 2 or more direct friends of the chosen user but
        are not directly connected to the chosen user</li>
</ul>
<h2>Requirements</h2>
<ul>
    <li>Use OO PHP5 and any software design patterns you think are appropriate for the
        implementation.</li>
    <li>You may use 3rd party PHP frameworks/libraries.</li>
</ul>
<h2>Notes</h2>
<p>This task is designed to give us an idea of how you approach programming problems. We will look at the structure of
    any database tables you create, as well as the structure of your code and whether or not the solution you create is
    extendable. We are looking at how efficient your algorithms are and also how you design the html and/or any
    javascript you may use.</p>
