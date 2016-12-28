//rotating thumb functions
var rotateThumbs = new Array();

function changeThumb(index, i, num_thumbs, path)
{
	if (rotateThumbs[index])
	{
		if(i<=num_thumbs){
			
		//strip index
		indexStr = index.replace(/[^0-9]/g, ''); 			

		document.getElementById(index).src = path + indexStr + '_' + i + ".jpg";
		i++;
		setTimeout("changeThumb('" + index + "'," + i + ", " + num_thumbs + ", '" + path + "')", 600);
		}else{
		changeThumb(index, 1, num_thumbs, path);
		}
	}
}

function thumbStart(index, num_thumbs, path)
//function startThumbChange(index, num_thumbs, path, cache_control)
{    
    rotateThumbs[index] = true;
    changeThumb(index, 1, num_thumbs, path);

}

function thumbStop(index, path, def)
{
    rotateThumbs[index] = false;
	
	//strip index
	indexStr = index.replace(/[^0-9]/g, ''); 	
	
    document.getElementById(index).src = path + indexStr + "_" + def + ".jpg";
}
