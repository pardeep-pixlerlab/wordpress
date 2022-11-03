//set editor colors
function allgreen(){
	var input = document.querySelector('.age1');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.backgroundColor = '#800000';
		element.style.color = '#fff';
		
	});  
}
function allred(){
	var input = document.querySelector('.age2');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.backgroundColor = '#808080';
		element.style.color = '#fff';
		
	});  
}
function allblue(){
	var input = document.querySelector('.age3');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.backgroundColor = '#228B22';
		element.style.color = '#fff';
		
	});  
}
//set editor font size 
function allfont1(){
	var input = document.querySelector('.font1');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.fontSize = '16px';
		element.style.fontWeight = 'bold';
	});  
}
function allfont2(){
	var input = document.querySelector('.font2');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.fontSize = '24px';
		element.style.fontWeight = 'bold';
	});  
}
function allfont3(){
	var input = document.querySelector('.font3');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.fontSize = '28px';
		element.style.fontWeight = '1200px';
	});  
}
 function allfont4(){
	var input = document.querySelector('.font4');
	addEventListener('click', () => {
		var element = document.querySelector('.setcolortext');
		element.style.fontSize = '35px';
		element.style.fontWeight = '1200px';
	});  
}
