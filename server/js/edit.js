function disp(){
    // 入力ダイアログを表示 ＋ 入力内容を user に代入
    user = window.prompt("入退室時間を入力してください");
    // 入力内容が tama の場合は example_tama.html にジャンプ
    if(user == 'tama'){
        location.href = "example_tama.html";
    }
    // 入力内容が hana の場合は example_hana.html にジャンプ
    else if(user == 'hana'){
        location.href = "example_hana.html";
    }
    // 入力内容が一致しない場合は警告ダイアログを表示
    else if(user != "" && user != null){
       window.alert(user + 'さんは登録されていません');
    }
    // 空の場合やキャンセルした場合は警告ダイアログを表示
    else{
        window.alert('キャンセルされました');
    }
}

// function createSelectBox(){
//   //連想配列の配列
//   var　staying_time = new Array(25);
//   for (var i=0; i<=24; i++){
//       var value = String(i);
//       var text = String(i) + '時間';
//       staying_time[i] = {val:value, txt:text};
//   }
//   //連想配列をループ処理で値を取り出してセレクトボックスにセットする
//   for(var i=0;i<staying_time.length;i++){
//     let op = document.createElement("option");
//     op.value = staying_time[i].val;
//     op.text = staying_time[i].txt;
//     document.getElementById("sel1").appendChild(op);
//   }
// };