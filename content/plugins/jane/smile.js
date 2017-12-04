var emotion = document.createElement('div');



$(emotion).attr('id', 'emotion');



var smiles = "";



for (var i = 1; i <= 12; i++) {



    smiles += "<img src='"+blog_url+"content/plugins/jane/face/" + i + ".gif' onclick='insertSmile(\"" + i + "\")' style=\"width:38px; height:38px\" />"



}



function insertSmile(id) {



    $('textarea[name=comment]').parseHtml('{smile:' + id + '}')



}



$.fn.extend({



    parseHtml: function(myValue) {



        var $t = $(this)[0];



        if (document.selection) {



            this.focus();



            sel = document.selection.createRange();



            sel.text = myValue;



            this.focus()



        } else if ($t.selectionStart || $t.selectionStart == '0') {



            var startPos = $t.selectionStart;



            var endPos = $t.selectionEnd;



            var scrollTop = $t.scrollTop;



            $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);



            this.focus();



            $t.selectionStart = startPos + myValue.length;



            $t.selectionEnd = startPos + myValue.length;



            $t.scrollTop = scrollTop



        } else {



            this.value += myValue;



            this.focus()



        }



    }



}) 



$('textarea[name=comment]').before($(emotion));



$(emotion).html(smiles);



