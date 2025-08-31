// Initialisation de la liste d'équipe (agents)
var buttonGroups, list = document.querySelectorAll(".team-list");

function onButtonGroupClick(e) {
	"list-view-button" === e.target.id || "list-view-button" === e.target.parentElement.id ? (document.getElementById("list-view-button").classList.add("active"), document.getElementById("grid-view-button").classList.remove("active"), Array.from(list).forEach(function (e) {
		e.classList.add("list-view-filter"), e.classList.remove("grid-view-filter")
	})) : (document.getElementById("grid-view-button").classList.add("active"), document.getElementById("list-view-button").classList.remove("active"), Array.from(list).forEach(function (e) {
		e.classList.remove("list-view-filter"), e.classList.add("grid-view-filter")
	}))
}
list && (buttonGroups = document.querySelectorAll(".filter-button")) && Array.from(buttonGroups).forEach(function (e) {
	e.addEventListener("click", onButtonGroupClick)
});
var url = "assets/json/",
	allmemberlist = "";

function loadTeamData(e) {
	var container = document.querySelector("#team-member-list");
	if (!container) return;
	container.innerHTML = "";
	Array.from(e).forEach(function (agent) {
		// Variables & fallback
		var favClass = agent.bookmark ? "active" : "";
		// avatar : priorité à agent_avatar puis memberImg
		var avatarSrc = agent.agent_avatar || agent.memberImg || '';
		var avatar = avatarSrc ? '<img src="' + avatarSrc + '" alt="" class="member-img img-fluid d-block rounded-circle" />' : '<div class="avatar-title border bg-light text-primary rounded-circle text-uppercase">' + (agent.nickname || '') + '</div>';
		// Champs additionnels (data-*). On suppose que l'API peut fournir ces champs.
		var dataAttrs = [
			'data-user-id="' + (agent.ID || agent.id || '') + '"',
			'data-user-login="' + (agent.user_login || '') + '"',
			'data-user-email="' + (agent.user_email || agent.agent_email || '') + '"',
			'data-user-status="' + (agent.user_status || '') + '"',
			'data-registration-date="' + (agent.registration_date || '') + '"',
			'data-agent-post-id="' + (agent.agent_post_id || '') + '"',
			'data-agent-name="' + (agent.agent_name || agent.memberName || '') + '"',
			'data-post-status="' + (agent.post_status || '') + '"',
			'data-agent-email="' + (agent.agent_email || agent.user_email || '') + '"',
			'data-agency-id="' + (agent.agency_id || '') + '"',
			'data-agency-name="' + (agent.agency_name || '') + '"',
			'data-phone="' + (agent.phone || '') + '"',
			'data-mobile="' + (agent.mobile || '') + '"',
			'data-whatsapp="' + (agent.whatsapp || '') + '"',
			'data-skype="' + (agent.skype || '') + '"',
			'data-website="' + (agent.website || '') + '"',
			'data-agent-avatar="' + (agent.agent_avatar || '') + '"',
			'data-position="' + (agent.position || agent.role || '') + '"',
			'data-facebook="' + (agent.facebook || '') + '"',
			'data-twitter="' + (agent.twitter || '') + '"',
			'data-linkedin="' + (agent.linkedin || '') + '"',
			'data-googleplus="' + (agent.googleplus || '') + '"',
			'data-youtube="' + (agent.youtube || '') + '"',
			'data-tiktok="' + (agent.tiktok || '') + '"',
			'data-instagram="' + (agent.instagram || '') + '"',
			'data-pinterest="' + (agent.pinterest || '') + '"',
			'data-vimeo="' + (agent.vimeo || '') + '"',
			'data-postal-code="' + (agent.postal_code || '') + '"'
		].join(' ');

        container.innerHTML += '<div class="col">'
            + '<div class="card team-box" ' + dataAttrs + '>'
            + '  <div class="team-cover">'
            + '    <img src="' + (agent.coverImg || 'assets/images/small/img-9.jpg') + '" alt="" class="img-fluid" />'
            + '  </div>'
            + '  <div class="card-body p-4">'
            + '    <div class="row align-items-center team-row">'
            + '      <div class="col team-settings">'
            + '        <div class="row">'
            + '          <div class="col">'
            + '            <div class="flex-shrink-0 me-2">'
            + '              <button type="button" class="btn btn-light btn-icon rounded-circle btn-sm favourite-btn ' + favClass + '"><i class="ri-star-fill fs-14"></i></button>'
            + '            </div>'
            + '          </div>'
            + '          <div class="col text-end dropdown">'
            + '            <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill fs-17"></i></a>'
            + '            <ul class="dropdown-menu dropdown-menu-end">'
            + '              <li><a class="dropdown-item edit-list" href="#addmemberModal" data-bs-toggle="modal" data-edit-id="' + (agent.id || '') + '"><i class="ri-pencil-line me-2 align-bottom text-muted"></i>Modifier</a></li>'
            + '              <li><a class="dropdown-item remove-list" href="#removeMemberModal" data-bs-toggle="modal" data-remove-id="' + (agent.id || '') + '"><i class="ri-delete-bin-5-line me-2 align-bottom text-muted"></i>Supprimer</a></li>'
            + '            </ul>'
            + '          </div>'
            + '        </div>'
            + '      </div>'
            + '      <div class="col-lg-4 col">'
            + '        <div class="team-profile-img">'
            + '          <div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0">' + avatar + '</div>'
            + '          <div class="team-content">'
            + '            <a class="member-name" data-bs-toggle="offcanvas" href="#member-overview" aria-controls="member-overview">'
            + '              <h5 class="fs-16 mb-1">' + (agent.memberName || agent.agent_name || 'Sans nom') + '</h5>'
            + '            </a>'
            + '            <p class="text-muted member-designation mb-0">' + (agent.position || agent.role || '') + '</p>'
            + '          </div>'
            + '        </div>'
            + '      </div>'
            + '      <div class="col-lg-4 col">'
            + '        <div class="row text-muted text-center">'
            + '          <div class="col-6 border-end border-end-dashed">'
            + '            <h5 class="mb-1 projects-num">' + (agent.projects || agent.properties || '0') + '</h5>'
            + '            <p class="text-muted mb-0">Propriétés</p>'
            + '          </div>'
            + '          <div class="col-6">'
            + '            <h5 class="mb-1 tasks-num">' + (agent.tasks || agent.transactions || '0') + '</h5>'
            + '            <p class="text-muted mb-0">Transactions</p>'
            + '          </div>'
            + '        </div>'
            + '      </div>'
            + '      <div class="col-lg-2 col">'
            + '        <div class="text-end">'
            + '          <a href="/Prolfile/agent/' + (agent.ID || agent.id || '') + '" class="btn btn-light view-btn">Voir Profil</a>'
            + '        </div>'
            + '      </div>'
            + '    </div>'
            + '  </div>'
            + '</div>'
            + '</div>';
	});
	bookmarkBtn();
	editMemberList();
	removeItem();
	memberDetailShow();
}

function bookmarkBtn() {
	Array.from(document.querySelectorAll(".favourite-btn")).forEach(function (e) {
		e.addEventListener("click", function () {
			e.classList.contains("active") ? e.classList.remove("active") : e.classList.add("active")
		})
	})
}
// Récupération des agents (JSON API)
fetch("https://crm.rebencia.com/Agent/json")
	.then(r => r.json())
	.then(data => {
		loadTeamData(allmemberlist = data)
	})
	.catch(err => console.error('Erreur chargement agents:', err));
bookmarkBtn();
var editlist = !1;

function editMemberList() {
	var r;
	Array.from(document.querySelectorAll(".edit-list")).forEach(function (t) {
		t.addEventListener("click", function () {
			r = t.getAttribute("data-edit-id"), allmemberlist = allmemberlist.map(function (e) {
				return e.id == r && (editlist = !0,
				document.getElementById("createMemberLabel").innerHTML = "Modifier le membre",
				document.getElementById("addNewMember").innerHTML = "Enregistrer",
				("" == e.memberImg ? document.getElementById("member-img").src = "assets/images/users/user-dummy-img.jpg" : document.getElementById("member-img").src = e.memberImg),
				document.getElementById("cover-img").src = e.coverImg,
				document.getElementById("memberid-input").value = e.id,
				document.getElementById("teammembersName").value = e.memberName,
				document.getElementById("designation").value = e.position,
				document.getElementById("project-input").value = e.projects,
				document.getElementById("task-input").value = e.tasks,
				document.getElementById("memberlist-form").classList.remove("was-validated")), e
			})
		})
	})
}

function fetchIdFromObj(e) {
	return parseInt(e.id)
}

function findNextId() {
	var e, t;
	return 0 === allmemberlist.length ? 0 : (e = fetchIdFromObj(allmemberlist[allmemberlist.length - 1])) <= (t = fetchIdFromObj(allmemberlist[0])) ? t + 1 : e + 1
}

function sortElementsById() {
	loadTeamData(allmemberlist.sort(function (e, t) {
		e = fetchIdFromObj(e), t = fetchIdFromObj(t);
		return t < e ? -1 : e < t ? 1 : 0
	}))
}

function removeItem() {
	var r;
	Array.from(document.querySelectorAll(".remove-list")).forEach(function (t) {
		t.addEventListener("click", function (e) {
			r = t.getAttribute("data-remove-id"), document.getElementById("remove-item").addEventListener("click", function () {
				var t;
				t = r, loadTeamData(allmemberlist = allmemberlist.filter(function (e) {
					return e.id != t
				})), document.getElementById("close-removeMemberModal").click()
			})
		})
	})
}

function memberDetailShow() {
	Array.from(document.querySelectorAll(".team-box")).forEach(function (a) {
		a.querySelector(".member-name").addEventListener("click", function () {
			var e = a.querySelector(".member-name h5").innerHTML,
				t = a.querySelector(".member-designation").innerHTML,
				r = a.querySelector(".member-img") ? a.querySelector(".member-img").src : "assets/images/users/user-dummy-img.jpg",
				m = a.querySelector(".team-cover img").src,
				i = a.querySelector(".projects-num").innerHTML,
				n = a.querySelector(".tasks-num").innerHTML;
			// Mise à jour des éléments principaux
			var ov = document.querySelector("#member-overview");
			ov && (ov.querySelector(".profile-img").src = r,
				ov.querySelector(".team-cover img").src = m,
				ov.querySelector(".profile-name").innerHTML = e,
				ov.querySelector(".profile-designation").innerHTML = t,
				ov.querySelector(".profile-project").innerHTML = i,
				ov.querySelector(".profile-task").innerHTML = n);

			// Détails supplémentaires de l'agent (data-*)
			var labels = {
				userId: "ID utilisateur",
				userLogin: "Identifiant",
				userEmail: "E-mail",
				userStatus: "Statut utilisateur",
				registrationDate: "Date d'inscription",
				agentPostId: "ID fiche agent",
				agentName: "Nom de l'agent",
				postStatus: "Statut de la fiche",
				agentEmail: "Email agent",
				agencyId: "ID agence",
				agencyName: "Nom de l'agence",
				position: "Poste / Fonction",
				agentAvatar: "Avatar",
				phone: "Téléphone",
				mobile: "Mobile",
				whatsapp: "WhatsApp",
				skype: "Skype",
				website: "Site web",
				facebook: "Facebook",
				twitter: "Twitter",
				linkedin: "LinkedIn",
				googleplus: "Google+",
				youtube: "YouTube",
				tiktok: "TikTok",
				instagram: "Instagram",
				pinterest: "Pinterest",
				vimeo: "Vimeo",
				postalCode: "Code postal"
			};
			var extraData = a.closest('.team-box').dataset;
			var rows = '';
			Object.keys(labels).forEach(function(k){
				// Conversion camelCase label -> dataset attribute (data-*)
				var attrName = k.replace(/([A-Z])/g, function(m){ return '-' + m.toLowerCase(); });
				if(k === 'postalCode') attrName = 'postal-code';
				var val = extraData[k] || extraData[attrName];
				if(typeof val === 'undefined' || val === '') return; // n'affiche pas les vides
				rows += '<tr><th class="text-muted fw-normal" style="width:40%">'+labels[k]+'</th><td>'+val+'</td></tr>';
			});
			if(rows){
				var target = ov ? (ov.querySelector('.agent-extra') || (function(){
					var body = ov.querySelector('.offcanvas-body') || ov;
					var d = document.createElement('div');
					d.className = 'agent-extra mt-3';
					body.appendChild(d);
					return d;
				})()) : null;
				if(target){
					target.innerHTML = '<h6 class="mb-2">Informations détaillées</h6><div class="table-responsive"><table class="table table-sm table-borderless mb-0">'+rows+'</table></div>';
				}
			}
		})
	})
}
document.querySelector("#member-image-input").addEventListener("change", function () {
		var e = document.querySelector("#member-img"),
			t = document.querySelector("#member-image-input").files[0],
			r = new FileReader;
		r.addEventListener("load", function () {
			e.src = r.result
		}, !1), t && r.readAsDataURL(t)
	}), document.querySelector("#cover-image-input").addEventListener("change", function () {
		var e = document.querySelector("#cover-img"),
			t = document.querySelector("#cover-image-input").files[0],
			r = new FileReader;
		r.addEventListener("load", function () {
			e.src = r.result
		}, !1), t && r.readAsDataURL(t)
	}), Array.from(document.querySelectorAll(".addMembers-modal")).forEach(function (e) {
		e.addEventListener("click", function () {
			document.getElementById("createMemberLabel").innerHTML = "Ajouter un membre", document.getElementById("addNewMember").innerHTML = "Ajouter", document.getElementById("teammembersName").value = "", document.getElementById("designation").value = "", document.getElementById("cover-img").src = "assets/images/small/img-9.jpg", document.getElementById("member-img").src = "assets/images/users/user-dummy-img.jpg", document.getElementById("memberlist-form").classList.remove("was-validated")
		})
	}),
	function () {
		"use strict";
		var e = document.querySelectorAll(".needs-validation");
		Array.prototype.slice.call(e).forEach(function (s) {
			s.addEventListener("submit", function (e) {
				var t, r, m, i, n, a, o, l;
				s.checkValidity() ? (e.preventDefault(), t = document.getElementById("teammembersName").value, r = document.getElementById("designation").value, m = document.getElementById("member-img").src, i = document.getElementById("cover-img").src, n = "assets/images/users/user-dummy-img.jpg" == m.substring(m.indexOf("/as") + 1) ? "" : m, a = t.match(/\b(\w)/g).join("").substring(0, 2), "" === t || "" === r || editlist ? "" !== t && "" !== r && editlist && (o = 0, o = document.getElementById("memberid-input").value, allmemberlist = allmemberlist.map(function (e) {
					return e.id == o ? {
						id: o,
						coverImg: i,
						bookmark: e.bookmark,
						memberImg: m,
						nickname: a,
						memberName: t,
						position: r,
						projects: document.getElementById("project-input").value,
						tasks: document.getElementById("task-input").value
					} : e
				}), editlist = !1) : (l = findNextId(), allmemberlist.push({
					id: l,
					coverImg: i,
					bookmark: !1,
					memberImg: n,
					nickname: a,
					memberName: t,
					position: r,
					projects: "0",
					tasks: "0"
				}), sortElementsById()), loadTeamData(allmemberlist), document.getElementById("createMemberBtn-close").click()) : (e.preventDefault(), e.stopPropagation()), s.classList.add("was-validated")
			}, !1)
		})
	}();
var searchMemberList = document.getElementById("searchMemberList");
searchMemberList.addEventListener("keyup", function () {
	var e = searchMemberList.value.toLowerCase();
	t = e;
	var t, e = allmemberlist.filter(function (e) {
		return -1 !== e.memberName.toLowerCase().indexOf(t.toLowerCase()) || -1 !== e.position.toLowerCase().indexOf(t.toLowerCase())
	});
	0 == e.length ? (document.getElementById("noresult").style.display = "block", document.getElementById("teamlist").style.display = "none") : (document.getElementById("noresult").style.display = "none", document.getElementById("teamlist").style.display = "block"), loadTeamData(e)
});