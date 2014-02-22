
from access import Access

from time import strftime

if __name__ == '__main__':

    access = Access()

    towns = [] 

    towns.append(['http://www.townofbrighton.org/','Town of Brighton','Brighton, NY'])
    towns.append(['http://www.brockportny.org/','Village of Brockport','Brockport, NY'])
    towns.append(['http://www.townofchili.org/','Town of Chili','Chili, NY'])
    towns.append(['http://www.churchville.net/','Town of Churchville','Churchville, NY'])
    towns.append(['http://www.clarksonny.org/','Town of Clarkson','Clarkson, NY'])
    towns.append(['http://eastrochester.org/','Town of East Rochester','East Rochester, NY'])
    towns.append(['http://www.village.fairport.ny.us/','Village of Fairport','Fairport, NY'])
    towns.append(['http://www.townofgates.org/','Town of Gates','Gates, NY'])
    towns.append(['http://greeceny.gov/','Town of Greece','Greece, NY'])
    towns.append(['http://www.hamlinny.org/','Town of Hamlin','Hamlin, NY'])
    towns.append(['http://www.henrietta.org/','Town of Henrietta','Henrietta, NY'])
    towns.append(['http://www.hiltonny.org/','Village of Hilton','Hilton, NY'])
    towns.append(['http://www.villageofhoneoyefalls.org/','Village of Honeoye Falls','Honeye Falls, NY'])
    towns.append(['http://www.townofmendon.org/','Town of Medon','Mendon, NY'])
    #towns.append(['http://www.ogdenny.com','Town of Ogden','Ogdon, NY',['http://www.ecode360.com/documents/list/OG0089/quick/']])
    towns.append(['http://www.parmany.org/','Town of Parma','Parma, NY'])
    towns.append(['http://www.penfield.org/','Town of Penfield','Penfield, NY'])
    towns.append(['http://www.perinton.org/','Town of Perinton','Perinton, NY'])
    towns.append(['http://townofpittsford.org/','Town of Pitsford','Pitsford, NY'])
    towns.append(['http://www.townofriga.org/','Town of Riga','Riga, NY'])
    towns.append(['http://www.cityofrochester.gov/','City of Rochester','Rochester, NY'])
    towns.append(['http://www.townofrush.com/','Town of Rush','Rush, NY'])
    towns.append(['http://www.scottsvilleny.org/','Village of Scottsville','Scottsville, NY'])
    towns.append(['http://www.vil.spencerport.ny.us/','Village of Specerport','Specerport, NY'])
    towns.append(['http://www.townofsweden.org/','Town of Sweden','Sweden, NY'])
    towns.append(['http://www.ci.webster.ny.us/','Town of Webster','Webster, NY'])
    towns.append(['http://www.villageofwebster.com/','Village of Webster','Webster, NY'])
    towns.append(['http://www.townofwheatland.org/','Town of Wheatland','Wheatland, NY'])

    for town in towns:

        entity = {
            'name': town[1],
            'description': town[2],
            'website':town[0],
            'urls': [
                
            ],
            'creationdatetime': str(strftime("%Y-%m-%d %H:%M:%S")),
        }

        access.addentity(entity)


