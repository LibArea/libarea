<?php
/*
 * Publication of posts and comments
 * Публикация постов и комментариев
 */

return [
    // Enable (true) merging of the first comment with the post if the author of the post = the author of the comment
    // Включить (true) слияние первого комментария с постом в том случае, если автор поста = автору комментария
    'merge_comment_post' => true,
    
    // Enable (true) merging of comments if a participant posts multiple comments one after the other on a single comment
    // Включить (true) слияние комментариев, если участник публикует множество комментариев один за другим на один комментарий
    'merge_comments' => true,
]; 