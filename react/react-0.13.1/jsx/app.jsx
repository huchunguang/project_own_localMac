class Count extends React.Component{
	constructor(props){
		this.state={count:0};
	}
	
	update(){
		this.setState({
			count:++ this.state.count
		});
	}
	
	render(){
		return (
			<div>
			<h1>Count</h1>
			<h3>{this.state}</h3>
			</div>
		);
	}
}
const element=<Count/>;
const count=React.render(element,document.getElementById('content'));
class Comment extends React.Component{
	render(){
		return (
			<div>
			<div className="comment-body">
				{this.props.children}
			</div>
			<div className="comment-author">
				{this.props.author}
			</div>
			</div>
		);
	}
}
var comments=[
	{author:"caojinghan",body:"this is my comment"},
];
var other=[	
	{author:"caojinghan",body:"this is my comment"},
	{author:"dannie",body:"this is a other body"},
];
class CommentList extends React.Component{
	render(){
		console.log(this.props.comments);
		var commentsNode=this.props.comments.map(function(comment,index){
			return <Comment key={'comment-'+index} author={comment.author}>{comment.body}</Comment>
		});
		return (
			<div>
				{commentsNode}
			</div>
		);
	}
}
class CommentForm extends React.Component{
	handleSubmit(e){
		e.preventDefault();
		const author=
		this.refs.author.getDOMNode().value.trim();
		const body=
		this.refs.body.getDOMNode().value.trim();
		const form=this.refs.form.getDOMNode();//拿到表单的对象
		console.log(author,body);
		this.props.onSubmit({author:author,body:body});
		form.reset();
		
	}
	render(){
		return (
			<form className="comment-form" ref="form" onSubmit={e=>{this.handleSubmit(e)}}>
				<input type="text" placeholder="Your name" ref="author"/>
				<input type="text" placeholder="input Your comment" ref="body"/>
				<input type="submit" value="Add Comment" />
				CommentForm
			</form>
		);
	}
}
class CommentBox extends React.Component{
	constructor(props){
		super();
		this.state={
			comments:props.comments
			};
	}
	loadDataFromServer(){
		$.ajax(
			{
				url:this.props.url,
				data:'username=huchunguang',
				dataType:'json',
				type:'GET',
				success:comments=>{
					this.setState({comments:comments});
					console.log(comments);
					//使用Ajax的success Method 绑定outside this context
				},
				error:(xhr,status,err)=>{
					console.log(err.toString());
				}
			}
		);
	}
	componentDidMount(){
		this.loadDataFromServer();
		console.log('render into DOM...');
	}
	handleNewComment(comment){
		const comments=this.state.comments;
		const newComments=comments.concat([comment]);
		this.setState({comments:newComments});
		setTimeout(()=>{
			$.ajax({
			url:this.props.url,
			dataType:'json',
			type:'POST',
			data:comment,
			success:comments=>{
				this.state.comments=({comments:comments});
				console.log();
			},
			error:(xhr,status,err)=>{
				console.log(err);
				this.setState({comments:comments});
			}
			});	
		},2000);
		
		console.log(comment);
	}
	render(){
		return (
			<div className="comment-box">
				Comment Box
				<h1>Comments</h1>
				<CommentList comments={this.state.comments}/>
				<CommentForm onSubmit={comment=>this.handleNewComment(comment)}/>
			</div>
		);
	}
}
const commentBox=<CommentBox comments={comments} url="loadDataFromServer.php"/>;
box=React.render(commentBox,document.getElementById('commentBox'));
