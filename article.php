<?php
error_reporting(E_ALL);
include("pdocon.php");
$con = pdoconn::Conn();
$msg = '';
if(isset($_POST['blog_comment']))
{		
    $comment=htmlspecialchars($_POST['comment'], ENT_QUOTES);
    $blog_id=$_POST['blog_id'];
	$list = array(':blog_id'=>$blog_id,':comment'=>$comment,':add_date'=>date('Y-m-d h:i:s'));
	$sqlc = 'INSERT INTO comment (blog_id,comment,add_date) VALUES (:blog_id,:comment,:add_date)';
	$queryc = $con->prepare($sqlc);
    $result = $queryc->execute($list);
    if( $result == true ){
        $msg = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Comment Added Succesfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Opps Some thing went wrong!</div>';
    }
}
if(isset($_POST['sub_blog_comment']))
{       
    $comment=htmlspecialchars($_POST['comment'], ENT_QUOTES);
    $id=$_POST['id'];
    $list = array(':id'=>$id,':sub_comment'=>$comment,':add_sub_date'=>date('Y-m-d h:i:s'));
    $sqlc = 'INSERT INTO sub_comment (id,sub_comment,add_sub_date) VALUES (:id,:sub_comment,:add_sub_date)';
    $queryc = $con->prepare($sqlc);
    $result = $queryc->execute($list);
    if( $result == true ){
        $msg = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Comment Added Succesfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Opps Some thing went wrong!</div>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Writing Minds Comment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
<h2 align="center">Writing Minds Blog</h2>
<div class="col-md-2"></div>
<div class="col-md-8">
    <h3>Uber Reportedly Raises New Funding Round, Now Valued At Over $50'Billion</h3>
    <p>Uber just closed a new round of funding that will value the company at more than $50 billion, according to the Wall Street Journal.

    The newspaper says that Uber has raised close to $1 billion in the round, which brings total equity financing to more than $5 billion.

    The WSJ reported that investors in this latest round include Microsoft and Indian media company Bennett, Coleman, and Company.

    This funding comes just a day after Uber announced it is committed to investing one billion dollars to grow its business in India.

    TechCrunch has reached out to Uber for comment, and will update this story when we receive more information.
    </p>  <hr>
    <?php 
        echo $msg;
        $sql ="select c.*,s.sub_comment,s.add_sub_date from comment c left join sub_comment s on c.id=s.id where c.blog_id=1 order by add_date,add_sub_date ASC";
        $query = $con->query($sql);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $get_commented_arr = array();
        foreach ($rows as $value) {
            $get_commented_arr[$value['id']][] = $value;
        }
        $get_all_comment = '';
        foreach ($get_commented_arr as $value) {
            $sub_comments = '';
            if( !empty($value[0]['sub_comment']) ){
                foreach ($value as $sub_val) {
                   $sub_comments .='<div class="clear"></div><div class="row"><div class="col-md-2"></div><div class="well col-md-10"><p>'.$sub_val['sub_comment'].'</p><div style="float:right;"><small>'.$sub_val['add_sub_date'].'</small></div></div></div><br>';
                }
            }
            $get_all_comment .='<div class="clear"></div><div class="well"><p>'.$value[0]['comment'].'</p><div style="float:right;"><a class="reply" href="javascript:void(0);" comment-id="'.$value[0]['id'].'">Reply</a>&nbsp;&nbsp;<small>'.$value[0]['add_date'].'</small></div><br></div><br>'.$sub_comments.'<div class="clear_all_form" id="form_id_'.$value[0]['id'].'"></div>';
        }
        echo $get_all_comment;
    ?>
    <div class="clear"></div>
    <div>
        <form action="" method="post">
            <input type="hidden" name="blog_id" value="1">
            <textarea type="text" class="form-control" name="comment" placeholder="add comment"></textarea> <br>
            <input type="submit" name="blog_comment" class="btn btn-primary" >
        </form>
    </div>  <br><br>
</div>
<div class="col-md-2"></div>
<div class="clear"></div><br>
<br>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $('.reply').on('click',function(){
        $('.clear_all_form').html('');
        var id = $(this).attr('comment-id');
        $('#form_id_'+id).html('<div class="row"><div class="col-md-12"><div class="col-md-2"><\/div><div class="col-md-10"><form action="" method="post"> <input type="hidden" name="id" value="'+id+'"><textarea type="text" class="form-control" name="comment" placeholder="add reply"><\/textarea><br><input type="submit" name="sub_blog_comment" class="btn btn-primary" ><\/form><\/div><\/div><\/div><br>');
    });
</script>