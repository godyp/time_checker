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