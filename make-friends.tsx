
import { IonPage, IonContent, IonHeader, IonButton } from "@ionic/react";
import { CSSProperties } from "react";
import TinderCard from 'react-tinder-card'
import './make-friends.css';


const MakeFriend: React.FC = () => {
    

    return (
      <IonPage>
        <IonContent scroll-y="false"> 
        
            <div className="make-friend">
                <div className="header">
                    <img src="assets/image/back2.png"  alt="Main-logo" />
                    <div className="note">
                    <i className="fa-regular fa-bell"></i>
                    <i className="fa-solid fa-plus plus"></i>
                    </div>
                </div>
                <div className="status">
                    <div className="all">
                        <div className="my-status">
                            <img src="assets/image/Avatar.png" className="my-stat others" alt=""/>
                            <i className="fa-solid fa-plus my-plus"></i>
                            <span className="names">My Story</span>
                        </div>
                    </div>
                    <div className="others"><img src="assets/image/third.png" alt="" /><span className="names">Selena</span></div>
                    <div className="others"><img src="assets/image/fourth.png" alt="" /><span className="names">Clara</span></div>
                    <div className="others"><img src="assets/image/fifth.png" alt="" /><span className="names">Fabian</span></div>
                    <div className=""><img src="assets/image/sixth.png" alt="" /><span className="names">George</span></div>
                </div>
                <div className="first-quarter">
                    <div className="first"><span className="first-love">Make Friends</span></div>
                    <div><span className="second">Search Partners</span></div>
                </div>
                

                /* Picture stack */

                <div className="picture-container">

                    # main picture
                    <div className="picture-detail">
                        <div className="details">
                            <div className="head-up">70% Match</div>
                            <div className="swipe">
                                <img src="assets/image/Rectangle.png" alt="" className="imm"/>
                                {/* <img src="assets/image/Rectangle.png" alt=""/>
                                <img src="assets/image/Rectangle.png" alt=""/>
                                <img src="assets/image/Rectangle.png" alt=""/>
                                <img src="assets/image/Rectangle.png" alt=""/> */}
                            </div>
                            <div className="picture-details">
                                <h2 className="sara">Sara Willaims</h2>
                                <div className="stateless-distance">
                                    <span className="stateless">Califonia, USA</span>
                                    <span className="distance">( 54 km )</span>
                                </div>
                                <div className="ico">
                                    <div className="star"><i className="fa-regular fa-xmark"></i></div>
                                    <div className="mid"><i className="fa-solid fa-heart"></i></div>
                                    <div className="star"><i className="fa-solid fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="picture-detail stack1"></div>
                    <div className="picture-detail stack2"></div>
                    <div className="picture-detail stack3"></div>

                </div>
                
                
                
                
                
                <div className="isale">
                    <div className=""><i className="fa-solid fa-star" style={{color: '#DCD7E1'}}></i></div>
                    <div className=""><i className="fa-solid fa-heart"style={{color: '#DCD7E1'}}></i></div>
                    <div className=""><i className="fa-solid fa-star"style={{color: '#DCD7E1'}}></i></div>
                    <div className=""><i className="fa-thin fa-messages"style={{color: '#DCD7E1'}}></i></div>
                    <div className=""><i className="fa-solid fa-user"style={{color: '#DCD7E1'}}></i></div>
                </div>
            </div>
        </IonContent>
    </IonPage>
    );
  };

  export default MakeFriend;
  
GET api/vi/user/roles
POST api/vi/user/roles

