import java.util.HashMap;

public class DataMemory {
	
	//Create a data memory
	private static HashMap<Integer,Integer> dataMem;
	
	//read a data given the address
	public static int getData(int addr) {
		return dataMem.get(addr);
	}
	
	//write a data into the memory
	public static void setData(int addr, int data) {
		dataMem.put(addr, data);
	}
}
