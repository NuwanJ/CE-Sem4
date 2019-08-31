
public class ALU {
	
	public static int getResult(int data1,int data2,int select) {
		int result=0;
		switch(select) {
			case 0:
				result = data1+data2;
				break;
			case 1:
				result = data1-data2;
				break;
			case 2:
				result = data1*data2;
				break;
			case 3:
				result = data1/data2;
				break;
			case 4:
				result = data1&data2;
				break;
			case 5:
				result = data1|data2;
				break;
			case 6:
				result = ~(data1|data2);
				break;
			case 7:
				result = data1^data2;
				break;
			case 8:
				result = data1>>data2;
				break;
			case 9:
				result = data1<<data2;
				break;
			case 10:
				result = (data1<data2)? 1:0;
				break;
			case 11:
				System.out.println("syscall");
				break;
			case 12:
				result = (data1==data2)? 1:0;
				break;
			case 13:
				result = (data1>data2)? 1:0;
				break;
			default :
				System.out.println("Error in ALU");	
		}
		return result;
	}
}
