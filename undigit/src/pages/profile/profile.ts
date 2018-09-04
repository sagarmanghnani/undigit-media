import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Http, Headers, Jsonp } from '@angular/http';
import 'rxjs/add/operator/map';

/**
 * Generated class for the ProfilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-profile',
  templateUrl: 'profile.html',
})
export class ProfilePage {
  //id:any = this.navParams.get('id');
  id:any = 1;
  profiling:Object[] = [];
  constructor(public navCtrl: NavController, public navParams: NavParams, public http:Http) {
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad ProfilePage');
    this.getProfile();
  }

  getProfile()
  {
    var data = {
      id:this.id
    }
    let headers = new Headers();
    headers.append('Content-type', 'application/json');

    this.http.post('http://localhost/undigit/undigit.php?rquest=profilePage', JSON.stringify(data), {headers:headers}).map(res => res.json()).subscribe(res => {
      
      
      this.profiling = res.profile;
      console.log(this.profiling);
      //this.profile = res.profile;
      //console.log(typeof(this.profiles));

    })
  }

}
